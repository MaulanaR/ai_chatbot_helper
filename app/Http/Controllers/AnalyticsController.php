<?php

namespace App\Http\Controllers;

use App\Models\Chatbot;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display overall analytics for all chatbots.
     */
    public function index()
    {
        $user = Auth::user();
        $chatbots = $user->chatbots()->with(['chatSessions', 'chatMessages'])->get();

        // Overall stats
        $totalChatbots = $chatbots->count();
        $totalSessions = ChatSession::whereIn('chatbot_id', $chatbots->pluck('id'))->count();
        $totalMessages = ChatMessage::whereIn('chatbot_id', $chatbots->pluck('id'))->count();
        $userMessages = ChatMessage::whereIn('chatbot_id', $chatbots->pluck('id'))->where('role', 'user')->count();

        // Messages per day (last 30 days)
        $messagesPerDay = ChatMessage::whereIn('chatbot_id', $chatbots->pluck('id'))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Per chatbot stats
        $chatbotStats = $chatbots->map(function ($chatbot) {
            return [
                'id' => $chatbot->id,
                'name' => $chatbot->name,
                'sessions' => $chatbot->chatSessions->count(),
                'messages' => $chatbot->chatMessages->count(),
                'lastActivity' => $chatbot->chatMessages->max('created_at'),
            ];
        })->sortByDesc('messages')->values();

        // Recent sessions across all chatbots
        $recentSessions = ChatSession::whereIn('chatbot_id', $chatbots->pluck('id'))
            ->with(['chatbot', 'messages' => function($query) {
                $query->orderBy('created_at', 'asc')->take(3);
            }])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return Inertia::render('Analytics/Index', [
            'stats' => [
                'totalChatbots' => $totalChatbots,
                'totalSessions' => $totalSessions,
                'totalMessages' => $totalMessages,
                'userMessages' => $userMessages,
                'avgMessagesPerSession' => $totalSessions > 0 ? round($totalMessages / $totalSessions, 1) : 0,
            ],
            'messagesPerDay' => $messagesPerDay,
            'chatbotStats' => $chatbotStats,
            'recentSessions' => $recentSessions,
        ]);
    }
}
