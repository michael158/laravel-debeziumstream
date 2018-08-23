<?php

namespace MichaelDouglas\DebeziumStream\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Console\Command;

class DetailKafkaConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:detail-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show Kafka Connections';

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
        $connector = $this->ask('connection name?');

        try {
            $response = $this->client->get($this->baseUrl . '/connectors/' . $connector .'/status');
        } catch (BadResponseException $e) {
            dd($e->getResponse()->getBody()->getContents());
        }

        $return = $this->formatResponse($response->getBody()->getContents());
        dd($return);
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
