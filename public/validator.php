<?php
/* here you have to accept a json as this file is serves as API point and json looks like 
* [ "user_email" : "userEmail" , "other" : "[...args...]"] 
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

    require_once dirname(__DIR__) . '\\private\\functions.php';

    $incomming = file_get_contents( 'php://input' );
    $incomming = json_decode( $incomming, true );
    
    $message = '';
    $status = false;
    $mode = 'unset';
    
    if( isset( $incomming[ 'user_email' ] ) && isset( $incomming[ 'other' ][ 'send_mail' ] ) )
    {
        $send_mail = $incomming[ 'other' ][ 'send_mail' ];
        if ( ! $send_mail) {
            if( isset( $incomming[ 'other' ][ 'verification_token' ] ))
            {
                $set_token = $incomming[ 'other' ][ 'verification_token' ];
                $mode = 'set';
            }
            elseif( isset( $incomming[ 'other' ][ 'unset_token' ] ))
            {
                $unset_token = $incomming[ 'other' ][ 'unset_token' ];
                $mode = 'unset';
            }
        }
        $user_email = $incomming[ 'user_email' ];
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
                            if ( ! $send_mail ) 
                            {
                                if( 'set' === $mode )
                                {
                                    if ( detokenised_it( $user_email, $set_token )) {
                                        $status = true;
                                        $message = 'verified';
                                        $query = sprintf( 'UPDATE `%s` SET `verified` = \'1\' ,`subcribed` = \'1\' WHERE `email` = ?', DB_TABLE );
                                        run_query( $query,[ $user_email ],[ PDO::PARAM_STR ] );
                                    }
                                    else {
                                        $status = false;
                                        $message = '';
                                    }
                                }else
                                {
                                    if ( unsubscribe_token_verify( $user_email, $unset_token )) {
                                        $status = true;
                                        $message = 'unscribed';
                                        $query = sprintf( 'UPDATE `%s` SET `verified` = \'0\' ,`subcribed` = \'0\' WHERE `email` = ?', DB_TABLE );
                                        run_query( $query,[ $user_email ],[ PDO::PARAM_STR ] );
                                    }
                                    else {
                                        $status = false;
                                        $message = '';
                                    }
                                }
                            }else {
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
                                    if ( $send_mail ) {
                                        send_email_for_verification( $user_email );
                                    }
                                }
                            }
                        }else 
                        {
                            $query = sprintf( 'INSERT INTO `%s` (`email`,`verified`,`subcribed`) VALUE (?,\'0\',\'0\')', DB_TABLE );
                            run_query( $query,[ $user_email ],[ PDO::PARAM_STR ] );
                            $message = 'Subscribed.\nPlease verify by clicking the verification link sent to you through email';
                            $status = true;
                            if ( $send_mail ) {
                                send_email_for_verification( $user_email );
                            }
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