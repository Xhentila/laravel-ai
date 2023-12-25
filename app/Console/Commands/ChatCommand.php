<?php

namespace App\Console\Commands;

use App\AI\Chat;
use Illuminate\Console\Command;
use function Laravel\Prompts\{outro, text, info, spin};


class ChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:chat (--system)';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $chat = new Chat();

        if($this->option('system')){
            $this->systemMessage($this->option('system'));
        }

        $question= text(
            label: 'What is your question for AI?',
            required: true
        );

        $response = spin(fn() => $chat->send($question), 'Loading Response...');

        info($response);

        while($question = text('Do you want to respond?')) {

            $response = spin(fn() => $chat->send($question), 'Loading Response...');

            info($response);
        }

        outro('Thank you for chating!');
    }
}
