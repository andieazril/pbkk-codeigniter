<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function login($username, $password)
    {
        $user = $this->db->get_where('users', array(
            'username' => $username
        ))->row();

        if ($user && password_verify($password, $user->password))
        {
            return $user;
        }

        return FALSE;
    }
}