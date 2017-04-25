Jsnlib
    composer require jsnlib/jsnlib
    composer require jsnlib/translg
    composer require jsnlib/assesbox
    composer require jsnlib/restful_client

install "chriskacerguis/codeigniter-restserver"
    drop config/rest.php
    drop language/english
    drop libraries/Format.php
         libraries/REST_Controller.php

update composer.json
    
    "autoload": {
        "classmap": [
            "application/libraries",
            "application/models",
            "application/helpers",
        ]
    }

update index.php
    case 'development':
        // error_reporting(-1);
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        ini_set('display_errors', 1);

create .htaccess
    DirectoryIndex index.php
    RewriteEngine on
    RewriteCond $1 !^(index\.php|images|css|js|robots\.txt|favicon\.ico)
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ ./index.php/$1 [L,QSA] 

update config.php
    $config['index_page'] = '';
    $config['composer_autoload'] = 'vendor/autoload.php';
    
Create 
    core/MY_Controller.php
        <?php

        class MY_Controller extends CI_Controller {}
        require_once("MY_APP_Controller.php");

    core/MY_APP_Controller.php
        ....

Create New API Controller "Controllers/Method.php"

    class Method extends MY_APP_Controller
    {
        function __construct()
        {
            parent::__construct();
        }

        public function index_get()
        {
            parent::respary(['Say' => 'Hello World']);
        }
    }

Visit
    GET /method