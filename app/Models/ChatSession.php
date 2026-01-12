<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'chatbot_id',
        'session_id',
        'visitor_ip',
        'user_agent',
    ];

    /**
     * Get the chatbot that owns this session.
     */
    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class);
    }

    /**
     * Get the messages for this session.
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
