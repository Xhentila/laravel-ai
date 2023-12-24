<?php

namespace App\AI;


use Illuminate\Support\Facades\Http;

class Chat
{
    protected array $messages = [];

    /**
     * @return array
     */
    public function send(string $message)
    {
        $this->messages[] = [
            'role' => 'assistant',


            'content' => $message
        ];

        $response = Http::withToken(config('services.openai.secret'))
            ->post('https://api.openai.com/v1/chat/completions', [
                "model" => "gpt-3.5-turbo",
                "messages" => $this->messages
            ])->json('choice.0.message.content');

        $this->messages[] = [
            'role' => 'user',
            'content' => $response
        ];

        return $response;
    }

    public function reply($message): array
    {
        return $this->send($message);
    }
    public function messages(): array
    {
        return $this->messages;
    }

    public function systemMessage($message): static
    {
        $this->messages[] = [
            'role' => 'system',
            'content' => $message
        ];
        return $this;
    }
}
