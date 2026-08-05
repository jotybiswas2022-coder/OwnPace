<?php

namespace App\Services\Campaigns;

use App\Models\Campaign;
use App\Models\InstallmentPlan;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * CampaignSegmentService — turns a campaign's audience + recipient_filters
 * into the concrete set of customer IDs to message.
 *
 * Segments:
 *   all              every active customer
 *   active_users     active and not suspended
 *   overdue_users    customers with at least one overdue installment
 *   plan_users       customers on any installment plan (or a specific one via
 *                    recipient_filters.plan_id)
 *   repeat_customers customers with N+ orders (recipient_filters.min_orders)
 *
 * A shared optional min_orders filter applies to every segment.
 */
class CampaignSegmentService
{
    public static function resolveIds(Campaign $campaign): Collection
    {
        $filters = is_array($campaign->recipient_filters) ? $campaign->recipient_filters : [];

        $query = User::query()
            ->where('is_active', true)
            ->where('is_suspended', false);

        switch ($campaign->audience) {
            case 'overdue_users':
                $query->whereHas('orders.installmentPayments', fn ($q) => $q->where('status', 'overdue'));
                break;

            case 'plan_users':
                $planId = (int) ($filters['plan_id'] ?? 0);
                $query->whereHas('orders.installmentPlan', function ($q) use ($planId) {
                    if ($planId > 0) {
                        $q->whereKey($planId);
                    }
                });
                break;

            case 'repeat_customers':
                $query->has('orders', '>=', (int) ($filters['min_orders'] ?? 2));
                break;

            case 'active_users':
            case 'all':
            default:
                break;
        }

        $minOrders = (int) ($filters['min_orders'] ?? 0);
        if ($minOrders > 1) {
            $query->has('orders', '>=', $minOrders);
        }

        return $query->pluck('id');
    }

    /**
     * Estimated recipient counts for each segment, shown on the compose form
     * so admins know the blast radius before hitting send.
     *
     * @return array<string, int>
     */
    public static function countBySegment(?int $planId = null): array
    {
        $active = fn () => User::where('is_active', true)->where('is_suspended', false);

        $counts = [
            'all' => $active()->count(),
            'active_users' => $active()->count(),
            'overdue_users' => $active()
                ->whereHas('orders.installmentPayments', fn ($q) => $q->where('status', 'overdue'))
                ->count(),
            'repeat_customers' => $active()->has('orders', '>=', 2)->count(),
        ];

        $plans = InstallmentPlan::query()->orderBy('name');
        if ($planId) {
            $plans->whereKey($planId);
        }

        $counts['plan_users'] = $active()
            ->whereHas('orders.installmentPlan')
            ->count();

        return $counts;
    }
}
