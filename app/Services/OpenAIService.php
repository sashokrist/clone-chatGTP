<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->client = new Client(['base_uri' => 'https://api.openai.com/v1/']);
    }

    public function getCompletion($messages)
    {
        $response = $this->client->post('chat/completions',  [
            'verify' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
