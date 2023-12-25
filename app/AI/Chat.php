<?php

namespace App\AI;


use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;


class Chat
{
    protected array $messages = [];

    /**
     * @return array
     */
    public function send(string $message, bool $speech = false): ?string
    {
        $this->messages[] = [
            'role' => 'assistant',


            'content' => $message
        ];

        $response = OpenAI::chat()->create([
                "model" => "gpt-3.5-turbo",
                "messages" => $this->messages
            ])->choice(0)->message->content;

        $this->messages[] = [
            'role' => 'user',
            'content' => $response
        ];

        return $speech ? $this->speech($response) : $response;
    }

    public function speech($message)
    {
         return OpenAI::audio()->speech([
            'model'=> 'tts-1',
            'inout'=>$message,
            'voice'=> 'alloy',
        ]);
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
