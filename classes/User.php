<?php

class User {
	
	protected $rawData;
	
	public $id;
	public $login;
	public $active;
	public $infomail;
	protected $roles;
	
	
	
	public function __construct($json) {
		$this->rawData = $json;
		
		$this->id = $json['id'];
		$this->login = $json['login'];
		$this->active = $json['active'];
		if (isset($json['infomail'])) {
			$this->infomail = $json['infomail'];
		} else {
			$this->infomail = false;
		}
		$this->roles = $json['roles'];
		
	}
	
	/**
	 * check is user is customer
	 * 
	 * @return boolean true if customer, false if agent or admin
	 */
	public function isCustomer() {
		
		foreach ($this->roles as $role) {
			if ($role == 'Customer' && $this->active) {
				return true;
			}
		}
		return false;
	}
}
?>