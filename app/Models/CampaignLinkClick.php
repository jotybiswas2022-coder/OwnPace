<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignLinkClick extends Model
{
    protected $fillable = ['campaign_log_id', 'url'];

    public function log()
    {
        return $this->belongsTo(CampaignLog::class, 'campaign_log_id');
    }
}
