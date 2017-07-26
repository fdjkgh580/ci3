<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * API 的位置，繼承擴充的程序 \Jsnlib\Codeigniter\REST_Controller 
 * 
 * 繼承依序為
 * MY_Controller
 * REST_Controller
 * \Jsnlib\Codeigniter\REST_Controller
 * MY_APP_Controller
 * 
 */
class MY_APP_Controller extends  \Jsnlib\Codeigniter\REST_Controller {

    function __construct()
    {
        parent::__construct();
    }

}