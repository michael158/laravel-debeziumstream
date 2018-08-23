<?php

namespace MichaelDouglas\DebeziumStream\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Console\Command;
use MichaelDouglas\DebeziumStream\Services\KAFKA\Data\DebeziumConnectors;

class ConnectDebezium extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:create-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Debezium connection with apache Kafka';

    protected $client;
    protected $baseUrl;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->baseUrl = env('KAFKA_CONNECT_HOST');

        $this->client = new Client(
            ['headers' =>
                [
                    'Content-Type' => 'application/json',
                ],
                'verify' => false
            ]);

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = $this->getData();

        try {
            $response = $this->client->post($this->baseUrl . '/connectors', [
                'json' => $data
            ]);
        } catch (BadResponseException $e) {
            dd($e->getResponse()->getBody()->getContents());
        }

        $return = $this->formatResponse($response->getBody()->getContents());
        dd($return);
    }

    private function getData()
    {
        $connector = DebeziumConnectors::$data[env('DEBEZIUM_DB_CONNECTOR')]['name'];
        $port = DebeziumConnectors::$data[env('DEBEZIUM_DB_CONNECTOR')]['port'];

        $data = [
            'name' => env('DEBEZIUM_DB_CONNECTOR_NAME', 'mysql-connector'),
            'config' => [
                'connector.class' => $connector,
                'database.hostname' => env('DEBEZIUM_DB_HOST', '127.0.0.1'),
                'database.port' => $port,
                'database.user' => env('DEBEZIUM_DB_USER', 'root'),
                'database.password' => env('DEBEZIUM_DB_PASSWORD', ''),
                'database.server.id' => env('DEBEZIUM_DB_SERVER_ID', ''),
                'database.server.name' => env('DEBEZIUM_DB_SERVER_NAME', 'local'),
                "database.whitelist" => env('DEBEZIUM_DB_DATABASE'),
                'database.history.kafka.bootstrap.servers' => env('KAFKA_HOST') . ':' . env('KAFKA_PORT', '9092'),
                'database.history.kafka.topic' => 'dbhistory.' . env('DEBEZIUM_DB_SERVER_NAME'),
                'include.schema.changes' => true,
                "event.deserialization.failure.handling.mode" => "ignore",
                "database.history.skip.unparseable.ddl" => true
            ]
        ];

        return $data;
    }

    /**
     * Formata a resposta da API para o sistema
     * @param $response
     * @return mixed
     */
    protected function formatResponse($response)
    {
        $return = json_decode($response, true);

        if (empty($return))
            $return = $response;

        return $return;
    }


}
