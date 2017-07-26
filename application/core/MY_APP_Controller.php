<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * API 的位置，繼承擴充的程序 \Lib\REST_Controller_Ext
 * 
 * 繼承依序為
 * MY_Controller
 * REST_Controller
 * Lib\REST_Controller_Ext
 * MY_APP_Controller
 * 
 */
class MY_APP_Controller extends \Lib\REST_Controller_Ext {

    function __construct()
    {
        parent::__construct();
    }

}