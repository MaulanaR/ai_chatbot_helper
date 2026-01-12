<?php

namespace App\Http\Controllers;

use App\Models\Chatbot;
use App\Models\KnowledgeBase;
use App\Services\PdfParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatbotController extends Controller
{
    private PdfParserService $pdfParser;

    public function __construct(PdfParserService $pdfParser)
    {
        $this->pdfParser = $pdfParser;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's chatbots.
     */
    public function index()
    {
        $chatbots = Auth::user()->chatbots()
            ->with('knowledgeBases')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Chatbots/Index', [
            'chatbots' => $chatbots
        ]);
    }

    /**
     * Show the form for creating a new chatbot.
     */
    public function create()
    {
        return Inertia::render('Chatbots/Create');
    }

    /**
     * Store a newly created chatbot in storage.
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Chatbot store request received', [
            'user_id' => Auth::id(),
            'all' => $request->all(),
            'files' => $request->allFiles(),
            'headers' => $request->headers->all(),
        ]);

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'system_prompt' => 'nullable|string',
                'knowledge_type' => 'required|in:text,pdf',
                'content' => 'required_if:knowledge_type,text|nullable|string',
                'pdf_file' => 'required_if:knowledge_type,pdf|nullable|file|mimes:pdf|max:10240',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            throw $e;
        }

        try {
            $chatbot = Auth::user()->chatbots()->create([
                'name' => $validated['name'],
                'system_prompt' => $validated['system_prompt'] ?? 'Anda adalah chatbot profesional yang dibentuk untuk membantu menjawab pertanyaan user berdasarkan informasi yang sudah diberikan.',
            ]);

            // Create knowledge base entry
            $content = '';
            $filePath = null;

            if ($validated['knowledge_type'] === 'text') {
                $content = $validated['content'];
            } elseif ($validated['knowledge_type'] === 'pdf') {
                $content = $this->pdfParser->extract($request->file('pdf_file'));
                $filePath = $request->file('pdf_file')->store('pdfs', 'public');
            }

            $chatbot->knowledgeBases()->create([
                'type' => $validated['knowledge_type'],
                'content' => $content,
                'file_path' => $filePath,
            ]);

            return redirect()->route('chatbots.index')
                ->with('success', 'Chatbot berhasil dibuat!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Chatbot creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membuat chatbot: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Display the specified chatbot.
     */
    public function show(Chatbot $chatbot)
    {
        $this->authorize('view', $chatbot);

        $chatbot->load('knowledgeBases');
        
        return Inertia::render('Chatbots/Show', [
            'chatbot' => $chatbot,
            'embedCode' => $chatbot->embed_code,
            'widgetUrl' => route('widget.show', $chatbot->uuid),
        ]);
    }

    /**
     * Show the form for editing the specified chatbot.
     */
    public function edit(Chatbot $chatbot)
    {
        $this->authorize('update', $chatbot);

        $chatbot->load('knowledgeBases');

        return Inertia::render('Chatbots/Edit', [
            'chatbot' => $chatbot
        ]);
    }

    /**
     * Update the specified chatbot in storage.
     */
    public function update(Request $request, Chatbot $chatbot)
    {
        $this->authorize('update', $chatbot);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'system_prompt' => 'nullable|string',
        ]);

        $chatbot->update($validated);

        return redirect()->route('chatbots.index')
            ->with('success', 'Chatbot berhasil diperbarui!');
    }

    /**
     * Remove the specified chatbot from storage.
     */
    public function destroy(Chatbot $chatbot)
    {
        $this->authorize('delete', $chatbot);

        $chatbot->delete();

        return redirect()->route('chatbots.index')
            ->with('success', 'Chatbot berhasil dihapus!');
    }

    /**
     * Display analytics for the specified chatbot.
     */
    public function analytics(Chatbot $chatbot)
    {
        $this->authorize('view', $chatbot);

        // Get statistics
        $totalSessions = $chatbot->chatSessions()->count();
        $totalMessages = $chatbot->chatMessages()->count();
        $userMessages = $chatbot->chatMessages()->where('role', 'user')->count();
        $assistantMessages = $chatbot->chatMessages()->where('role', 'assistant')->count();

        // Messages per day (last 30 days)
        $messagesPerDay = $chatbot->chatMessages()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Recent sessions with messages
        $recentSessions = $chatbot->chatSessions()
            ->with(['messages' => function($query) {
                $query->orderBy('created_at', 'asc');
            }])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        // Top questions (most common user messages)
        $topQuestions = $chatbot->chatMessages()
            ->where('role', 'user')
            ->selectRaw('content, COUNT(*) as count')
            ->groupBy('content')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return Inertia::render('Chatbots/Analytics', [
            'chatbot' => $chatbot,
            'stats' => [
                'totalSessions' => $totalSessions,
                'totalMessages' => $totalMessages,
                'userMessages' => $userMessages,
                'assistantMessages' => $assistantMessages,
                'avgMessagesPerSession' => $totalSessions > 0 ? round($totalMessages / $totalSessions, 1) : 0,
            ],
            'messagesPerDay' => $messagesPerDay,
            'recentSessions' => $recentSessions,
            'topQuestions' => $topQuestions,
        ]);
    }
}
