<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LlmEngineService
{
    private string $baseUrl;
    private ?string $apiKey;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.llm_engine.url');
        $this->apiKey = config('services.llm_engine.key');
        $this->timeout = config('services.llm_engine.timeout', 360);
    }

    /**
     * Generate response from LLM engine.
     *
     * @param string $systemPrompt
     * @param string $context
     * @param string $userMessage
     * @return string
     */
    public function generateResponse(string $systemPrompt, string $context, string $userMessage): string
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/chat/completions', [
                    'model' => 'openai/gpt-oss-120b',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt . "\n\n" . 
                                         "Knowledge/Context:\n" . $context . "\n\n" .
                                         "Today is " . now()->format('Y-m-d') . "\n\n" .
                                         "User's timezone is " . now()->format('e') . "\n\n" .
                                         "User's IP address is " . request()->ip() . "\n\n" .
                                         "User's browser is " . request()->header('User-Agent') . "\n\n" .
                                         "Instructions: Anda adalah bagian dari perusahaan ini, jadi giring agar pengguna membeli produk/jasa yang disediakan. jadi tidak perlu memberitahu pengguna bahwa anda diberikan knowledge, hanya berikan jawaban. Berikan jawaban dalam format Plain text, tanpa markdown. Gunakan bahasa yang formal namun tidak terlalu kaku."
                        ],
                        [
                            'role' => 'user',
                            'content' => $userMessage
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 1000,
                ]);

                if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak dapat memberikan jawaban saat ini.';
            }

            throw new \Exception('LLM Engine returned error: ' . $response);

        } catch (\Exception $e) {
            Log::error('LLM Engine Error 1: ' . $e);
            Log::error('LLM Engine Error 2: ' . $e->getMessage());
            
            // Return user-friendly error message
            return 'Maaf, server sedang sibuk. Silakan coba lagi nanti.';
        }
    }

    // prompt construction logic moved directly into generateResponse for better standard protocol adherence

    /**
     * Check if the LLM engine is available.
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)->get($this->baseUrl . '/health');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
