<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MessageReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reasons() : BelongsToMany {
        return $this->belongsToMany(MessageReportReason::class, 'message_report_message_report_reasons', 'report_id', 'reason_id')->withTimestamps();
    }

    public function message() : BelongsTo {
        return $this->belongsTo(Message::class);
    }
}
