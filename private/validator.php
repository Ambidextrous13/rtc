<?php
/* here you have to accept a json as this file is serves as API point and json looks like 
* [ "user_email" : "userEmail" ] 
* sanitize it, validate it, search into Database and set it accordingly
*Expected json have two keys which are
* [
*    "message" : "Meaningfull String",
*    "verified" : "Boolean"
* ]
* if veriied then add email into database with appropiate values
*/    

    header( 'Content-type: application/json' );
    header( 'Access-Control-Allow-Origin: *' );
    header( 'Access-Control-Allow-Methods: POST' );

    $recieved = file_get_contents( 'php://input' );
    $recieved = json_decode( $recieved, true );
    $user_email = $recieved[ 'user_email' ];

    $message = '';
    $status = false;

    require_once __DIR__ . '\\functions.php';

    if( isset( $user_email ) )
    {
        if( $user_email  )
        {
            if( htmlentities( $user_email, ENT_QUOTES ) === $user_email )
            {
                if( filter_var( $user_email,FILTER_VALIDATE_EMAIL ) )
                {
                    if( filter_var( $user_email, FILTER_SANITIZE_EMAIL ) === $user_email )
                    {
                        $query = sprintf( 'SELECT `verified`,`subcribed` from `%s` WHERE `email`=?', DB_TABLE );
                        $result = run_query( $query,[ $user_email ],[ PDO::PARAM_STR ] );
                        if ( isset( $result[ '0' ] )) 
                        {
                            [ 'verified' => $is_verified , 'subcribed' => $is_subscribed ] = $result[ 0 ];
                            if ( $is_verified ) 
                            {
                                if ( $is_subscribed ) 
                                {
                                    $message = 'You have already subscribed';
                                    $status = true;
                                }else 
                                {
                                    $message = 'Subcription re-activated\nThank You';
                                    $query = sprintf( 'UPDATE `%s` SET `subcribed` = \'1\' WHERE `email` = ?', DB_TABLE );
                                    run_query( $query,[ $user_email ],[ PDO::PARAM_STR ] );
                                    $status = true;                                    
                                }
                            }else 
                            {
                                $message = 'Verification link has been sent to you mail id please verify yourself';
                                $status = true;
                                //Send Verification//////////////////////////////////////////////////////////////////////////////////////
                            }
                        }else 
                        {
                            $query = sprintf( 'INSERT INTO `%s` (`email`,`verified`,`subcribed`) VALUE (?,\'0\',\'0\')', DB_TABLE );
                            run_query( $query,[ $user_email ],[ PDO::PARAM_STR ] );
                            $message = 'Subscribed.\nPlease verify by clicking the verification link sent to you through email';
                            $status = true;
                            //Send Verification//////////////////////////////////////////////////////////////////////////////////////
                        }
                    }else
                    {
                        $message = 'Please enter valid email id ';
                    }
                }else
                {
                    $message = 'Please enter valid Email id ';
                }
            }else
            {
                $message = 'Please enter valid Email id ';
            }
        }else
        {
            $message = 'Email feild can not be empty';
        }
    }
    $flushing_json = [
            'message' => $message,
            'status'=>$status
            ];
    echo json_encode($flushing_json);
?>