<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Message extends Model
{
    use HasFactory;

    protected $guarded =[];

    protected $date = ['read_at'];

    public function conversation() : BelongsTo {
        return $this->belongsTo(Conversation::class);
    }

    public function reports() : BelongsToMany {
        return $this->belongsToMany(MessageReport::class, 'message_message_reports', 'message_id', 'report_id')->withTimestamps();
    }

    public function isRead() : bool {
        return $this->read_at != null;
    }
}
