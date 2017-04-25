composer require jsnlib/jsnlib
composer require jsnlib/translg
composer require jsnlib/assesbox

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

