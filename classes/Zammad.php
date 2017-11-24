<?php

class Zammad {
	
	protected $apiurl = '';
	protected $mode = '';
	protected $token = '';
	protected $con;
	
	/**
	 * API Mode User
	 */
	const MODE_USER = 'users';
	
	/**
	 * new Zammad API
	 * 
	 * @param String $url API url
	 * @param String $token auth token
	 */
	public function __construct($url, $token) {
		$this->apiurl = $url;
		$this->token = $token;
		
		$this->con = curl_init();
		curl_setopt($this->con, CURLOPT_HTTPHEADER, array('Authorization: Token token=' . $this->token));
		curl_setopt($this->con, CURLOPT_RETURNTRANSFER, true);
		
		//global $certbundle;
		//curl_setopt($this->con, CURLOPT_CAINFO, $certbundle);
		//curl_setopt($this->con, CURLOPT_CAPATH, $certbundle);
		//FIXME enable ssl verify
		curl_setopt($this->con, CURLOPT_SSL_VERIFYPEER, false);
	}
	
	protected function exec() {
		$res=array();
		$page = 1;
		
		do {
			$resPaged = $this->execAPI($page);
			$page++;
			//$res.=$resPaged;
			//var_dump(json_decode($resPaged, true));
			$res = array_merge($res, json_decode($resPaged, true));
		} while (count(json_decode($resPaged)) >= 500);
		//var_dump($res);
		//var_dump(json_decode($res));
		return $res;
	}
	
	protected function execAPI($page = 1) {
		$paging = '&page='.$page.'&per_page=500';
		//var_dump($this->apiurl . $this->mode);
		curl_setopt($this->con, CURLOPT_URL, $this->apiurl . $this->mode . $paging);
		return curl_exec($this->con);
	}
	
	protected function setMode($mode, $parameter='') {
		
		switch ($mode) {
			
			case self::MODE_USER:
				$this->mode = self::MODE_USER;
				break;
			
		}
		$this->mode.= $parameter;
		$this->mode.= '?expand=true';
	}
	
	/**
	 * check API connection
	 * 
	 * @return boolean true if connect to zammad api successful, false otherwise
	 */
	public function checkConnection() {
		$this->setMode(self::MODE_USER, '/me');
						
		return $this->exec();
	}
	
	/**
	 * get all customer from zammad
	 * 
	 * @return Array all Users
	 */
	public function getAllCustomer() {
		$this->setMode(self::MODE_USER);
		$users = $this->exec();
		$result = array();
		//var_dump($users);
		if (is_array($users)) {
			//var_dump('usrarray');
			foreach ($users as $jsonuser) {
				$usr = new User($jsonuser);
				if ($usr->isCustomer()) {
					$result[] = $usr;
				}
			}
		}
		return $result;
	}
	
	
	/**
	 * Update user, set new infomail value
	 * 
	 * @param int $userId - id of user to update
	 * @param boolean $infomail new infomail value (true/false)
	 * @return User updated user
	 */
	public function setInfoMail($userId, $infomail) {
		if (!is_numeric($userId) || !is_bool($infomail)) {
			return false;
		}
		$this->setMode(self::MODE_USER,'/' . $userId);
		
		$postData = array('infomail' => $infomail);
		
		curl_setopt($this->con, CURLOPT_POST, true);
		curl_setopt($this->con, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($this->con, CURLOPT_CUSTOMREQUEST, 'PUT');

		$user = $this->exec();
		return new User($user);
	}
}

?>