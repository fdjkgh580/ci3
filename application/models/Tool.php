<?php
namespace Model;
 
trait Tool {
    
    protected $ci;
    protected $db;
 
    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->database();
        $this->db = $this->ci->db;
    }
}