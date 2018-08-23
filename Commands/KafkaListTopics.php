<?php

namespace MichaelDouglas\DebeziumStream\Commands;

use App\Customer;
use Illuminate\Console\Command;
use MichaelDouglas\DebeziumStream\Services\KAFKA\KafkaService;

class KafkaListTopics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:list-topics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all topics runing in Kafka';

    protected $kafkaService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(KafkaService $kafkaService)
    {
        $this->kafkaService = $kafkaService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $topics =  $this->kafkaService->getTopics();
       $headers = ['topic_name'];

       $this->table($headers, $topics);
    }
}
