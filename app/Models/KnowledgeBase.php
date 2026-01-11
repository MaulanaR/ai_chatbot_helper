<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chatbot_id',
        'type',
        'file_path',
        'content',
    ];

    /**
     * Get the chatbot that owns the knowledge base.
     */
    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class);
    }
}
