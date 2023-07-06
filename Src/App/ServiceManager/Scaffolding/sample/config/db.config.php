<?php

return [
    "connection_pool" => [
        /** Feel free to add  more and you can switch / connect to anyone using the DbConnection class */
        "server1" => [
            "host" => "localhost",
            "user" => "postgres",
            "password" => "abcd1234",
            "dbname" => "test",
            "port" => 5432,
            "driver" => \Emma\Dbal\Connection\Drivers::PostGreSQL,
            "dsn" => "{dbms}:host={host};port={port};dbname={db}",
            //If connection failed, try specifying the PDO connection string using the 'dsn' field.
        ], 
        "server2" => [
            "host" => "localhost",
            "user" => "root",
            "password" => "",
            "dbname" => "test",
            "port" => 3306,
            "driver" => \Emma\Dbal\Connection\Drivers::MYSQL,
            "dsn" => "{dbms}:host={host};port={port};dbname={db}",
        ], 
        "stagging" => [
            "host" => "localhost",
            "user" => "root",
            "password" => "",
            "dbname" => "test",
            "port" => 3306,
            "driver" => \Emma\Dbal\Connection\Drivers::MYSQL,
            "dsn" => "{dbms}:host={host};port={port};dbname={db}",
        ], 
        "live" => [
            "host" => "localhost",
            "user" => "root",
            "password" => "",
            "dbname" => "test",
            "port" => 3306,
            "driver" => \Emma\Dbal\Connection\Drivers::MYSQL,
            "dsn" => "{dbms}:host={host};port={port};dbname={db}",
        ],
        /** DEFAULT CONNECTION FOR EMMA/ORM ON BOOT */
        "default" => "server2"
    ], 
];
