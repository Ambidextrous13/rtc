<?php
    require_once __DIR__ . '\\_DEFINATIONS_.php';

    function connect_database(){
        $database = 'mysql:host=' . DB_SERVER . ';dbname=' . DATABASE . ';';
        $connect = new PDO( $database, DB_USERNAME, DB_PASSWORD );
        return $connect;
    }

    function run_query( $query,$params,$datatypes ){
        $connection = connect_database();
        if( $connection ){
            $sql = $connection->prepare( $query );
            if( $params ){
                $len = count( $params );
                for( $iterator = 0; $iterator < $len; $iterator++ ){
                    $sql->bindParam( $iterator + 1, $params[ $iterator ], $datatypes[ $iterator ] );
                }
            }
            $sql->execute();
            $results = $sql->fetchAll( PDO::FETCH_ASSOC );
            $connection = null;
            return $results;
        }else{
            return null;
        }
    }

?>