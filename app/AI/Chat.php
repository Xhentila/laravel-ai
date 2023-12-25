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

    public function __construct(string $message)
    {
        $this->messages = $message;
    }

    public function send(string $message, bool $speech = false): ?string
    {
        $this->addMessage($message, 'assistant');

        $response = OpenAI::chat()->create([
                "model" => "gpt-3.5-turbo",
                "messages" => $this->messages
            ])->choices(0)->message->content;

        $this->addMessage($response);

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
        $this->addMessage($message, 'system');

        return $this;
    }

    public function visualize(string $description, array $options = []): static
    {
        $this->addMessage($description, 'system');

        $description = collect($this->messages)->where('role', 'user')->pluck('content')->implode(' ');

        $options = array_merge([
            'prompt' => $description,
            'model' => 'dall-e-3'
        ], $options);

        $url = OpenAI::images()->create($options)->data[0]->url;

        $this->addMessage($url);

        return $url;
    }

    public function addMessage( string $content, string $role = 'user')
    {
        return $this->messages[] = [
            'role' => $role,
            'content' => $content
        ];

    }
}
