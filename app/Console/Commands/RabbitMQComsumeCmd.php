<?php

namespace App\Console\Commands;

use App\Services\RabbitMQService;
use Illuminate\Console\Command;

use Laravel\Octane\Facades\Octane;

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
        $ocrtane = Octane::concurrently([
            fn () => $this->_run("outlet3"),
            fn () => $this->_run("outlet2"),
            fn () => $this->_run("outlet1"),

        ]);
    }


    private function _run($uid)
    {
        $ser_name = "service_" . $uid;

       $$ser_name = new RabbitMQService($uid);
       $$ser_name->consume();
    }
}
