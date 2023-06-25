<?php

namespace App\Console\Commands;

use App\Services\RabbitMQService;
use Illuminate\Console\Command;

class RabbitMQComsumeCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'RabbitMQ Comsume';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mqService = new RabbitMQService();
        $mqService->consume();
    }
}
