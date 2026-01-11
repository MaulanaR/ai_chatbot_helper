<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chatbot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'uuid',
        'system_prompt',
    ];

    /**
     * Get the user that owns the chatbot.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the knowledge bases for the chatbot.
     */
    public function knowledgeBases()
    {
        return $this->hasMany(KnowledgeBase::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($chatbot) {
            if (empty($chatbot->uuid)) {
                $chatbot->uuid = Str::uuid();
            }
        });
    }

    /**
     * Get the public URL for the chatbot widget.
     */
    public function getWidgetUrlAttribute()
    {
        return url("/widget/{$this->uuid}");
    }

    /**
     * Get the embed code for the chatbot widget.
     */
    public function getEmbedCodeAttribute()
    {
        return "<iframe src=\"{$this->widget_url}\" width=\"400\" height=\"600\"></iframe>";
    }
}
