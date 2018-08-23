<?php

namespace MichaelDouglas\DebeziumStream\Commands;

use Illuminate\Console\Command;
use MichaelDouglas\DebeziumStream\Services\KAFKA\KafkaService;

class KafkaListen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:listen {--topic=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init kafka consumer';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Init kafka consumer work');

        $topic = $this->option('topic');
        $service = new KafkaService($topic);
        $service->run();
    }

}
