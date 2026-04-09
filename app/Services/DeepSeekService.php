<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class DeepSeekService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.deepseek.api_key');
        $this->baseUrl = rtrim(config('services.deepseek.base_url', 'https://api.deepseek.com'), '/');
    }

    public function chat(array $messages, $model = 'deepseek-chat', $stream = false)
    {
        $url = $this->baseUrl . '/chat/completions';

        $response = Http::timeout(3000)->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'model' => $model,
            'messages' => $messages,
            'stream' => $stream,
        ]);

        if ($response->failed()) {
            throw new \Exception('DeepSeek API request failed: ' . $response->body());
        }

        return $response->json();
    }
}