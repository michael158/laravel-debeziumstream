# Laravel Debezium Stream Consumer

### Requirements
1.  Guzzle HTTP
2.  Laravel 5.2+

### Install Library
in folder of project run: 
```composer
$ composer require michaeldouglas/debeziumstream
```
### Configure Apache KAFKA and DEBEZIUM
Insert into .env file the folowers config lke example:
```text
KAFKA_HOST=18.231.179.169
KAFKA_PORT=9092
KAFKA_CONNECT_HOST=18.231.179.169:8083"
KAFKA_CONSUMER_BINARY="kafka-console-consumer"
KAFKA_CONSUMER_BINARY_PATH=/usr/local/etc/confluent/bin
 
DEBEZIUM_DB_CONNECTOR=mysql
DEBEZIUM_DB_CONNECTOR_NAME=otrs3-connector
DEBEZIUM_DB_HOST=informe.cvzmrvhpwyub.sa-east-1.rds.amazonaws.com
DEBEZIUM_DB_DATABASE=otrs5
DEBEZIUM_DB_SERVER_ID=791461872
DEBEZIUM_DB_SERVER_NAME=localdb
DEBEZIUM_DB_USER=informe_db
DEBEZIUM_DB_PASSWORD=3AJMekduV65d9ANj
DEBEZIUM_DB_STREAMING_TABLE=customer_user

```
### Configure Service Provider
In file config\app.php inside providers put the code:
```text
 MichaelDouglas\DebeziumStream\Providers\DebeziumKafkaServiceProvider::class
```
### Publish config files
```text
$ php artisan vendor:publish
```
### Configure class to receive KAFKA EVENTS
In file config\kafka.php
```text
 /*
    |--------------------------------------------------------------------------
    | Handle Class
    |--------------------------------------------------------------------------
    | The class who will receive Kafka Topic Messages
    */
    'handler_class' => 'your class here',
 
    /*
    |--------------------------------------------------------------------------
    | Handle Methods
    |--------------------------------------------------------------------------
    | The class methods who will receiver Kafka Events
    */
    'event_methods' => [
       'create' => 'your method here',
       'update' => 'your method here',
       'delete' => 'your method here'
    ]
```
The params look like this example:
```text
class TestService
{


    public function create($after, $topic)
    {

    }


    public function update($before, $after, $topic)
    {

    }



    public function delete($before, $topic)
    {

    }


}
```

### Init Kafka Consumer Work
```text
$ php artisan kafka:listen 
$ php artisan kafka:listen --topic=topic_name
```

# laravel-debeziumstream
