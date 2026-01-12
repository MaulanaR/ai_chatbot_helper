<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_session_id',
        'chatbot_id',
        'role',
        'content',
    ];

    /**
     * Get the session that owns this message.
     */
    public function session()
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }

    /**
     * Get the chatbot that this message belongs to.
     */
    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class);
    }
}
