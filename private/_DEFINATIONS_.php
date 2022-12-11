<?php
    require_once __DIR__. '\\_CONFIDENTIALS_.php';

    if( ! defined('DB_SERVER')){
        define( 'DB_SERVER', 'localhost' );
    }
    if( ! defined('DB_USERNAME')){
        define( 'DB_USERNAME', 'root' );
    }
    if( ! defined('DATABASE')){
        define( 'DATABASE', 'rtcamp_assignment' );
    }
    if( ! defined('DB_TABLE')){
        define( 'DB_TABLE', 'email_info' );
    }


    if( ! defined('PRIVATE_URI')){
        define( 'PRIVATE_URI', __DIR__ . '\\' );
    }

    if( ! defined( 'CSS_URI' )){
        define( 'CSS_URI' , PRIVATE_URI . 'assets\\css\\' );
    }

    if( ! defined( 'CSS_URL' )){
        define( 'CSS_URL' , CSS_URI . 'style.css' );
    }

    if( ! defined( 'JS_URI' )){
        define( 'JS_URI' , PRIVATE_URI . 'assets\\js\\' );
    }

    if( ! defined( 'JS_URL' )){
        define( 'JS_URL' , JS_URI . 'main.js' );
    }




    if( ! defined('PUBLIC_URI')){
        define( 'PUBLIC_URI' , dirname( __DIR__ ) . '\\public\\' );
    }

    if( ! defined('THE_HEADER')){
        define( 'THE_HEADER' , PUBLIC_URI . 'header.php' );
    }

    if( ! defined('THE_FOOTER')){
        define( 'THE_FOOTER' , PUBLIC_URI . 'footer.php' );
    }

    if( ! defined('TEMPLATE_PARTS_URI')){
        define( 'TEMPLATE_PARTS_URI' , PUBLIC_URI . 'template-parts\\' );
    }

    if( ! defined('INDEX_PART')){
        define( 'INDEX_PART', TEMPLATE_PARTS_URI . 'index.php' );
    }



    if( ! defined( 'HOME_TITLE' )){
        define( 'HOME_TITLE' , 'XKCD challenge' );
    }
?>