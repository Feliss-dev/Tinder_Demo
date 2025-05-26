<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reasons() {
        return $this->belongsToMany(MessageReportReason::class, 'message_report_message_report_reasons', 'report_id', 'reason_id')->withTimestamps();
    }
}
