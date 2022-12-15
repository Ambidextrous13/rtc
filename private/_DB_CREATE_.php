<?php
    require_once __DIR__ . '\\_DEFINATIONS_.php';
    define( 'CREATE_DATABASE', [
        "CREATE DATABASE `rtcamp_assignment`",
        "CREATE TABLE `rtcamp_assignment`.`email_info` ( `email` VARCHAR(255) NOT NULL , `create_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `verified` BOOLEAN NOT NULL DEFAULT FALSE , `subcribed` BOOLEAN NOT NULL DEFAULT FALSE )",
        "ALTER TABLE `email_info` ADD UNIQUE(`email`)",
    ]);
    try {
        $connect = new PDO( "mysql:host=" . DB_SERVER, DB_USERNAME, DB_PASSWORD );
        $connect -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $query = CREATE_DATABASE[0];
        $connect->exec($query);
        $connect = null;
        $database = 'mysql:host=' . DB_SERVER  . ';dbname=' .  DATABASE. ';';
        $connect = new PDO( $database, DB_USERNAME, DB_PASSWORD );
        foreach ( CREATE_DATABASE as $query) {
            if( preg_match( '/DATABASE/',$query ) ){
                continue;
            }
            $connect->exec($query);
        }
        $connect = null;

        die('Database Created Successfully');
    } 
    catch( PDOException $error ) {
        echo $error->getMessage();
        $connection = null;
    }


?>