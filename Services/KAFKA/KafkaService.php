<?php

namespace MichaelDouglas\DebeziumStream\Services\KAFKA;

use Symfony\Component\Process\Process;

class KafkaService
{
    private $kafkaHandlerService;

    private $kafkaServer;
    private $kafkaHost;
    private $binary;
    private $binaryPath;
    private $topic;

    public function __construct($topic = null)
    {
        $this->kafkaHandlerService = app()->make(KafkaHandlerService::class);

        $this->kafkaServer =  env('KAFKA_HOST', '127.0.0.1'). ':' . env('KAFKA_PORT', '9092');
        $this->kafkaHost = env('KAFKA_HOST', '127.0.0.1');
        $this->binaryPath = env('KAFKA_CONSUMER_BINARY_PATH', '');
        $this->binary = env('KAFKA_CONSUMER_BINARY', 'kafka-console-consumdasder');
        $this->topic = is_null($topic)
            ? env('DEBEZIUM_DB_SERVER_NAME') . '.' . env('DEBEZIUM_DB_DATABASE') . '.' . env('DEBEZIUM_DB_STREAMING_TABLE')
            : $topic;
    }

    public function consumeTopics()
    {
        $process = new Process($this->binaryPath. $this->binary .' --bootstrap-server '. $this->kafkaServer .' --property schema.registry.url=http://'. $this->kafkaHost .':8081 --topic ' . $this->topic);
        $process->setTimeout(0);

        $process->start();

        foreach ($process as $type => $message) {
            if ($process::OUT === $type) {
                $this->kafkaHandlerService->handleMessage($message, $this->topic);
            } else { // $process::ERR === $type
                echo "\nRead from stderr: " . $message;
            }
        }
    }


    public function getTopics()
    {
        $process = new Process($this->binaryPath. 'kafka-topics --list --zookeeper '. $this->kafkaHost .':2181');
        $process->start();
        $topics = [];

        $i=0;
        foreach ($process as $type => $message) {
            if ($process::OUT === $type) {
                if($message != "\n"){
                     $topics[$i]['topic_name'] = trim(preg_replace('/\s+/', ' ', $message));
                      $i++;
                }
            } else { // $process::ERR === $type
                echo "\nRead from stderr: " . $message;
            }
        }

        return $topics;
    }


}
