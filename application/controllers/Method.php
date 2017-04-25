<?php 

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