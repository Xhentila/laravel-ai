<?php

namespace App\Console\Commands;

use App\AI\Chat;
use Illuminate\Console\Command;

class ChatCommand extends Command
{
    // string
    protected $signature = 'chat';
   // string
    protected $description = 'Command description';

    public function handle()
    {
        $question= $this->ask('What is your question for AI?');
        dd($question);
        $chat = new Chat();
        $response = $chat->send($question);
        $this->info($response);
    }
}


