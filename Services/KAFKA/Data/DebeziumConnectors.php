<?php

namespace MichaelDouglas\DebeziumStream\Services\KAFKA\Data;


class DebeziumConnectors
{
    const MY_SQL    = 'mysql';
    const PG_SQL    = 'pgsql';
    const MONGO_DB  = 'mongodb';
    const ORACLE_DB = 'oracle';

    public static $data = [
        'mysql'   => ['id' => 'mysql',    'name' => 'io.debezium.connector.mysql.MySqlConnector',         'port' => '3306'],
        'pgsql'   => ['id' => 'pgsql',    'name' => 'io.debezium.connector.postgresql.PostgresConnector', 'port' => '5432'],
        'mongodb' => ['id' => 'mongodb',  'name' => 'io.debezium.connector.mongodb.MongoDbConnector',     'port' => '27017'],
        'oracle'  => ['id' => 'oracle',   'name' => 'io.debezium.connector.oracle.OracleConnector',       'port' => '1521'],
        'sqlsrv'  => ['id' => 'sqlsrv',   'name' => 'io.debezium.connector.sqlserver.SqlServerConnector',       'port' => '1433'],
    ];

}