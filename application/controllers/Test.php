<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }
    
    public function facebooklogin()
    {
        $this->load->view('test/facebooklogin', array('error' => ' ' ));
    }
    
    public function signup()
    {
        $this->load->view('test/signup', array('error' => ' ' ));
    }
    
    public function change_profile()
    {
        $this->load->view('test/change_profile', array('error' => ' ' ));
    }
}

?>