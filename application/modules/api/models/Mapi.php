<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mapi extends CI_Model
{
	public function get_user($data_user)
	{
		$this->db->select('users.*');
		$this->db->from('users');
		$this->db->join('access', 'users.access_id = access.id');
		foreach ($data_user as $key => $value) {
			// Where looping
			$this->db->where("users.$key", $value);
		}
		$query = $this->db->get();
		return $query;
	}
}
