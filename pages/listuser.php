<?php 

$html->setTitle('list user');

$html->addContent('<h3>Blacklist (Spam)</h3>');
$html->addContent('<table id="blTable" class="display">');
$html->addContent('<thead><tr><th>id</th><th>login</th><th>action</th></thead>');
$html->addContent('<tbody>');

$qBL = "SELECT b.id, c.login FROM blacklist b LEFT JOIN customer c ON b.id=c.id";
$resBL = mysqli_query($db, $qBL);
$dbBL = array();
while ($row = mysqli_fetch_assoc($resBL)) {
	$dbBL[$row['id']] = $row;
	$html->addContent('<tr><td>'.$row['id'].'</td><td>'.$row['login'].'</td><td> [no spam] </td></tr>');
}
	
$html->addContent('</tbody></table>');



$html->addContent('<h3>all Customers</h3>');
$html->addContent('<table id="userTable" class="display">');
$html->addContent('<thead><tr><th>id</th><th>login</th><th>action</th></thead>');
$html->addContent('<tbody>');


$qBL = "SELECT * FROM customer ";
$resBL = mysqli_query($db, $qBL);
$db = array();
while ($row = mysqli_fetch_assoc($resBL)) {
	$db[$row['id']] = $row;
	
	if (isset($dbBL[$row['id']])) {
		$action = '';
	} else {
		$action = '[spam]';
	}
	
	if ($row['infomail']) {
		$action.= ' [no mail]';
	} else {
		$action.= ' [mail]';
	}
	
	
	$html->addContent('<tr><td>'.$row['id'].'</td><td>'.$row['login'].'</td><td> '.$action.' </td></tr>');
}

$html->addContent('</tbody></table>');
$html->addContent("<script type=\"text/javascript\">$(document).ready(function(){
						$('#userTable').DataTable({'autoWidth': true});
						$('#blTable').DataTable();
					});</script>");


?>