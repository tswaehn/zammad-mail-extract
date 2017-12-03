<?php 

require_once('config.php');
require_once './classes/Zammad.php';
require_once './classes/User.php';

if (isset($_GET['action']) && !empty($_GET['action'])) {
	$action = $_GET['action'];

	$db = mysqli_connect($mysqlhost, $mysqluser, $mysqlpw, $mysqldb);
	$z = new Zammad($zammadapi, $zammadtoken);
} else {
	$action = 'none';
}



switch ($action) {
		
	case 'setspam':
		
		break;
	
	case 'setinfomail':
		if (isset($_GET['user']) && is_numeric($_GET['user'])) {
			if (isset($_GET['infomail']) && is_numeric($_GET['infomail'])) {
				if ($_GET['infomail'] ==1 ) {
					$infomail = true;
					$infomailint = 1;
				} else {
					$infomail = false;
					$infomailint = 0;
				}
				
				$z->setInfoMail((integer)$_GET['user'], $infomail);
				$q = "UPDATE customer SET infomail = $infomailint WHERE id = " . $_GET['user'];
				$r = mysqli_query($db, $q);
				echo 'OK';
				
			} else {
				echo 'invalid infomail parameter';
			}
		} else {
			echo 'invalid user id';
		}
		break;
		
	default:
		echo 'action unknown';
}
	
	

?>