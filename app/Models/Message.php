<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded =[];

    protected $date = ['read_at'];

    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }

    public function reports() {
        return $this->belongsToMany(MessageReportReason::class, 'message_report_reason_messages', 'message_id', 'reason_id');
    }

    public function isRead() : bool {
        return $this->read_at != null;
    }
}
