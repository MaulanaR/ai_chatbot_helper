<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Chatbot;
use App\Services\LlmEngineService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class ChatWidgetController extends Controller
{
    private LlmEngineService $llmEngine;

    public function __construct(LlmEngineService $llmEngine)
    {
        $this->llmEngine = $llmEngine;
    }

    /**
     * Display the chat widget for the specified chatbot UUID.
     */
    public function show($uuid)
    {
        $chatbot = Chatbot::where('uuid', $uuid)->firstOrFail();

        return Inertia::render('Widget/ChatWindow', [
            'chatbot' => [
                'name' => $chatbot->name,
                'uuid' => $chatbot->uuid,
                'systemPrompt' => $chatbot->system_prompt
            ]
        ]);
    }

    /**
     * Handle chat message and return LLM response.
     */
    public function sendMessage(Request $request, $uuid): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string',
        ]);

        $chatbot = Chatbot::where('uuid', $uuid)->firstOrFail();

        // Get or create chat session
        $sessionId = $request->session_id ?? session()->getId();
        $chatSession = \App\Models\ChatSession::firstOrCreate(
            ['session_id' => $sessionId],
            [
                'chatbot_id' => $chatbot->id,
                'visitor_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

        // Save user message
        \App\Models\ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'chatbot_id' => $chatbot->id,
            'role' => 'user',
            'content' => $request->message,
        ]);

        // Get all knowledge base content for this chatbot
        $knowledgeBase = $chatbot->knowledgeBases()
            ->orderBy('created_at', 'desc')
            ->get();

        $context = $knowledgeBase->pluck('content')->implode("\n\n");

        // Generate response using LLM engine
        $response = $this->llmEngine->generateResponse(
            $chatbot->system_prompt,
            $context,
            $request->message
        );

        // Save assistant response
        \App\Models\ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'chatbot_id' => $chatbot->id,
            'role' => 'assistant',
            'content' => $response,
        ]);

        return response()->json([
            'reply' => $response,
            'timestamp' => now()->toISOString(),
            'session_id' => $sessionId,
        ]);
    }

    /**
     * Get chatbot info for the widget.
     */
    public function info($uuid): JsonResponse
    {
        $chatbot = Chatbot::where('uuid', $uuid)->firstOrFail();

        return response()->json([
            'name' => $chatbot->name,
            'uuid' => $chatbot->uuid
        ]);
    }
}
