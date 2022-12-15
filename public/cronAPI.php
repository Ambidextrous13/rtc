<?php
    header( 'Content-type: application/json' );
    header( 'Access-Control-Allow-Origin: *' );
    header( 'Access-Control-Allow-Methods: POST' );

    $incomming = file_get_contents( 'php://input' );
    $incomming = json_decode( $incomming, true );

    if( isset( $incomming['API_token'] ) ){
        if ( 'ILoveInternetExplorer' === $incomming['API_token'] ) {
            require_once dirname(__DIR__) . '\\private\\functions.php';

            $query = sprintf('SELECT `email` FROM `%s` WHERE `verified` = 1 AND `subcribed` = 1',DB_TABLE);
            $result = run_query($query,[],[]);
            
            $flush_data = 'Error';

            if ( count($result) ) {

                $xkcd_latest_json = json_decode( file_get_contents( 'https://xkcd.com/info.0.json' ), true );
                if ( isset( $xkcd_latest_json[ 'num' ] ) ) {
                    $comic_number = rand( 1, $xkcd_latest_json[ 'num' ] );
                }
                else { 
                    $comic_number = rand( 1, 2710 );
                }
                $the_comic = json_decode( file_get_contents ( sprintf( 'https://xkcd.com/%s/info.0.json', strval( $comic_number ) ) ), true );
                
                $bundle = [
                    'comic_name' => $the_comic[ 'safe_title' ],
                    'comic_link' => 'https://xkcd.com/' . $the_comic[ 'num' ],
                    'comic_image' => $the_comic[ 'img' ],
                    'comic_transcript' => $the_comic[ 'transcript' ],
                    'comic_alt' => $the_comic[ 'alt' ],
                    'comic_name' => $the_comic[ 'title' ]
                ];

                foreach( $result as $row => $collomn ){
                    $to = $collomn[ 'email' ];
                    $token = unsubscribe_token( $to );
                    
                    $unsubscribe_link = UNSUBSCRIBE_PAGE . sprintf('?email=%s&token=%s',$to,$token);
                    $subject = sprintf( 'XKCD Commicbook: %s', $the_comic[ 'safe_title' ]);
                    require_once EMAIL_COMIC_PART;
                    $content = email_template( $unsubscribe_link, $bundle );
                    send_mail( $to, $subject, $content, ['comic'=>$bundle]);
                }
                $flush_data = 'Success!!';
            }
        }
    }
  
?>