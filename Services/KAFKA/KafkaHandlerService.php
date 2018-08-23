<?php

namespace MichaelDouglas\DebeziumStream\Services\KAFKA;

/**
 *
 * Classe Responsável por receber as mensagens do KAFKA e direcionar para uma serviço especificado no arquivo de configuração
 * Class KafkaHandlerService
 * @package App\Services\KAFKA
 */
class KafkaHandlerService
{
    private $config;
    private $instanceClass;

    public function __construct()
    {
        $this->config = config()->get('kafka');

        try {
            $this->instanceClass = app()->make($this->config['handler_class']);
        } catch (\Exception $e) {
            throw new \Exception('You must configure class to receive messages from kafka before listen');
        }
    }

    public function handleMessage($message, $topic)
    {
        try {
            $dataMessage = $this->proccessAndGetData(json_decode($message, true), $topic);

            // CREATE //
            if (empty($dataMessage['before']) && !empty($dataMessage['after']))
                $this->handleCreate($dataMessage['after'], $topic);

            // UPDATE //
            if(!empty($dataMessage['before']) && !empty($dataMessage['after']))
                $this->handleUpdate($dataMessage['before'],$dataMessage['after'] ,$topic);

            // DELETE
            if(!empty($dataMessage['before']) && empty($dataMessage['after']))
                $this->handleDelete($dataMessage['before'], $topic);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function proccessAndGetData($data, $topic)
    {
        $returnData = [];

        if (env('KAFKA_CONSUMER_BINARY') == 'kafka-avro-console-consumer') {
            $returnData['before'] = isset($data['before']) ? $data['before'][$topic . '.Value'] : null;
            $returnData['after'] = isset($data['after']) ? $data['after'][$topic . '.Value'] : null;
        }else{
            $returnData['before'] = isset($data['payload']['before']) ? $data['payload']['before'] : null;
            $returnData['after'] = isset($data['payload']['after']) ? $data['payload']['after'] : null;
        }

        return $returnData;
    }

    protected function handleCreate($after, $topic)
    {
        $this->instanceClass->{$this->config['event_methods']['create']}($after, $topic);
    }

    protected function handleUpdate($before, $after, $topic)
    {
        $this->instanceClass->{$this->config['event_methods']['update']}($before, $after, $topic);
    }

    protected function handleDelete($before, $topic)
    {
        $this->instanceClass->{$this->config['event_methods']['delete']}($before, $topic);
    }

}