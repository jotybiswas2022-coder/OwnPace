<?php

namespace Tests\Unit\Services;

use App\Models\Campaign;
use App\Models\InstallmentPayment;
use App\Models\Notification as AppNotification;
use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Services\Campaigns\CampaignSegmentService;
use App\Services\Messaging\NotificationChannels;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CommunicationTest extends TestCase
{
    use RefreshDatabase;

    private function orderFor(User $user): Order
    {
        return Order::create([
            'order_number' => 'ORD-TEST-'.uniqid(),
            'user_id' => $user->id,
            'status' => 'partial_paid',
            'grand_total' => 1000,
            'paid_amount' => 200,
            'remaining_amount' => 800,
            'payment_type' => 'installment',
        ]);
    }

    // ===== CHANNEL TOGGLES =====

    public function test_notification_channels_default_to_all(): void
    {
        $this->assertSame(['mail', 'sms', 'database'], NotificationChannels::for('payment_due'));
        $this->assertSame(['mail', 'sms', 'database'], NotificationChannels::for('order_status'));
    }

    public function test_notification_channels_respect_settings_map(): void
    {
        Setting::create(['notification_channels' => ['payment_due' => ['sms']]]);

        $this->assertSame(['sms'], NotificationChannels::for('payment_due'));
        $this->assertSame(['mail', 'sms', 'database'], NotificationChannels::for('delivery_confirmation'));
    }

    public function test_empty_channel_list_disables_the_type(): void
    {
        Setting::create(['notification_channels' => ['payment_due' => []]]);

        $this->assertSame([], NotificationChannels::for('payment_due'));
    }

    // ===== PAYMENT DUE REMINDERS (deduped) =====

    public function test_payment_due_reminder_dispatches_once_per_installment(): void
    {
        $user = User::factory()->create(['email' => 'buyer@example.com', 'phone' => '08012345678']);
        $order = $this->orderFor($user);
        InstallmentPayment::create([
            'order_id' => $order->id,
            'installment_number' => 1,
            'amount' => 200,
            'due_date' => now()->addDays(2),
            'status' => 'pending',
            'paid_amount' => 0,
        ]);

        Artisan::call('installments:send-reminders', ['--days' => 3]);
        Artisan::call('installments:send-reminders', ['--days' => 3]);

        $this->assertSame(1, NotificationLog::count());
        $this->assertSame(1, AppNotification::count());
        $this->assertSame('payment_due', AppNotification::first()->type);
    }

    public function test_payment_due_reminder_skips_already_paid(): void
    {
        $user = User::factory()->create(['email' => 'buyer@example.com']);
        $order = $this->orderFor($user);
        InstallmentPayment::create([
            'order_id' => $order->id,
            'installment_number' => 1,
            'amount' => 200,
            'due_date' => now()->addDays(2),
            'status' => 'paid',
            'paid_amount' => 200,
        ]);

        Artisan::call('installments:send-reminders', ['--days' => 3]);

        $this->assertSame(0, NotificationLog::count());
    }

    // ===== OVERDUE (marked once, notified once) =====

    public function test_mark_overdue_marks_and_notifies_once(): void
    {
        $user = User::factory()->create(['email' => 'buyer@example.com']);
        $order = $this->orderFor($user);
        $payment = InstallmentPayment::create([
            'order_id' => $order->id,
            'installment_number' => 1,
            'amount' => 200,
            'due_date' => now()->subDay(),
            'status' => 'pending',
            'paid_amount' => 0,
        ]);

        Artisan::call('installments:mark-overdue');
        Artisan::call('installments:mark-overdue');

        $this->assertSame('overdue', $payment->fresh()->status);
        $this->assertSame(1, NotificationLog::count());
        $this->assertSame(1, AppNotification::count());
        $this->assertSame('payment_overdue', AppNotification::first()->type);
    }

    // ===== TRANSPORT FALLBACKS =====

    public function test_mailer_factory_uses_env_fallback_when_no_smtp_configured(): void
    {
        $ok = \App\Services\Messaging\MailerFactory::send('test@example.com', 'Subject', '<p>Hi</p>');

        $this->assertTrue($ok);
    }

    public function test_sms_logs_when_no_provider_configured(): void
    {
        [$ok, $id] = \App\Services\Messaging\SmsService::send('08012345678', 'Hello there');

        $this->assertTrue($ok);
        $this->assertNotNull($id);
        $this->assertSame('+2348012345678', \App\Services\Messaging\SmsService::normalizePhone('08012345678'));
    }

    // ===== CAMPAIGN SEGMENTS =====

    public function test_overdue_segment_only_includes_overdue_customers(): void
    {
        $withOverdue = User::factory()->create();
        $clean = User::factory()->create();

        $campaign = Campaign::create([
            'name' => 'Test',
            'content' => 'Hello',
            'channel' => 'email',
            'audience' => 'overdue_users',
            'status' => 'draft',
        ]);

        // Seed an overdue payment for $withOverdue.
        $order = $this->orderFor($withOverdue);
        InstallmentPayment::create([
            'order_id' => $order->id,
            'installment_number' => 1,
            'amount' => 200,
            'due_date' => now()->subDay(),
            'status' => 'overdue',
            'paid_amount' => 0,
        ]);

        $ids = CampaignSegmentService::resolveIds($campaign);

        $this->assertTrue($ids->contains($withOverdue->id));
        $this->assertFalse($ids->contains($clean->id));
    }

    public function test_plan_segment_filters_by_plan_id(): void
    {
        $user = User::factory()->create();
        $order = $this->orderFor($user);

        $campaign = Campaign::create([
            'name' => 'Test',
            'content' => 'Hello',
            'channel' => 'email',
            'audience' => 'plan_users',
            'recipient_filters' => ['plan_id' => 999],
            'status' => 'draft',
        ]);

        $ids = CampaignSegmentService::resolveIds($campaign);

        $this->assertFalse($ids->contains($user->id));
    }
}
