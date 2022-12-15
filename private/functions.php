<?php
    require_once __DIR__ . '\\_DEFINATIONS_.php';

    function connect_database(){
        $database = 'mysql:host=' . DB_SERVER . ';dbname=' . DATABASE . ';';
        $connect = new PDO( $database, DB_USERNAME, DB_PASSWORD );
        return $connect;
    }

    function run_query( $query, $params, $datatypes ){
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

    function send_mail( $to, $subject, $content ,$attachments = [] ){
        $headers = array(
            'Authorization: Bearer '.SENDGRID_API_KEY,
            'Content-Type: application/json'
        );

        $data = [
            'personalizations' => [
                                    [
                                    'to' => [
                                              [
                                                'email' => $to,
                                              ]
                                            ] 
                                    ]    
                                ],
            'from' => [
                'email' => EMAIL_FROM ,
                'name' => EMAIL_BY,
                ],
            'subject' => $subject,
            'content' => [
                            [
                                'type' => 'text/html',
                                'value' => $content

                            ]
                        ],
                     ];
                     
        if( $attachments ){
            if( isset($attachments[ 'comic' ]) ){
                $data[ 'attachments' ] = [
                    [
                        'content' => base64_encode(file_get_contents( $attachments[ 'comic' ][ 'comic_image' ] )),
                        'type' => 'image/jpg',
                        'filename' => 'comic-'.$attachments[ 'comic' ][ 'comic_name' ],
                        'disposition' => 'attachment',
                        'content_ID' => 'comic-'.$attachments[ 'comic' ][ 'comic_name' ],
                    ]
                ];
            }
        }     
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send' );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        $response = curl_exec( $ch );
        curl_close( $ch );

        return $response;
    }

    function home_url(){
        // return sprintf(
        //     "%s://%s",
        //     isset( $_SERVER[ 'HTTPS' ] ) && 'off' !== $_SERVER[ 'HTTPS' ] ? 'https' : 'http',
        //     $_SERVER[ 'SERVER_NAME' ]
        //   );
        return 'http://localhost:8080/RTC_v3/public/';
    }

    function tokenised_it( $string ){
        return password_hash( $string . 'verification', PASSWORD_BCRYPT );
    }

    function detokenised_it( $email, $token ){
        return password_verify( $email . 'verification', $token );
    }

    function unsubscribe_token( $string ){
        return password_hash( $string . 'unsubscribe',PASSWORD_BCRYPT );
    }

    function unsubscribe_token_verify( $email, $token ){
        return password_verify( $email . 'unsubscribe', $token );
    }

    function send_email_for_verification( $to ){
        $subject = 'XKCD Comicbook verfication';
        $token = tokenised_it( $to );
        require_once __DIR__. '\\verification-email.php';
        $content = content( $to, $token );
        send_mail( $to, $subject, $content );
    }

?>