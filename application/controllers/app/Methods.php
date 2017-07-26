<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *  提供給前端，可測試連接或拋送的數據
 */
class Methods extends MY_APP_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $this->set_response($this->get(), REST_Controller::HTTP_OK);
    }

    public function index_post()
    {
        $this->set_response($this->post(), REST_Controller::HTTP_OK);
    }

    public function index_put()
    {
        $this->set_response($this->put(), REST_Controller::HTTP_OK);
    }

    public function index_delete()
    {
        $this->set_response($this->delete(), REST_Controller::HTTP_OK);
    }

    public function index_patch()
    {
        $this->set_response($this->post(), REST_Controller::HTTP_OK);
    }

}

/* End of file Methods.php */
/* Location: ./application/controllers/Methods.php */