<?php 

require_once('config.php');
require_once './classes/HTML.php';

if (isset($_GET['action']) && !empty($_GET['action'])) {
	$action = $_GET['action'];
} else {
	//default action: index
	$action = 'index';
}

$html = new HTML();
$db = mysqli_connect($mysqlhost, $mysqluser, $mysqlpw, $mysqldb);


switch ($action) {
	
	case 'syncuser':
		require_once './classes/User.php';
		require_once './classes/Zammad.php';
		include './pages/syncuser.php';
		break;
	
	case 'listuser':
		require_once './classes/User.php';
		include './pages/listuser.php';
		break;
	
	case 'sendmail':
		require_once './classes/User.php';
		include './pages/sendmail.php';
		break;
		
	case 'index':
	default:
		include './pages/main.php';
}

echo $html->render();
//phpinfo();
?>