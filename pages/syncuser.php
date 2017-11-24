<?php 


$html->setTitle('sync user');
$html->addContent('starting sync ' . date($dateformat), true);

$z = new Zammad($zammadapi, $zammadtoken);
//var_dump($z->checkConnection());
if ($z->checkConnection()) {
	$users = $z->getAllCustomer();
	$html->addContent('found ' . count($users) . ' users in zammad', true);
	
	
	$q = "SELECT * FROM customer";
	$res = mysqli_query($db, $q);
	$html->addContent('found ' . mysqli_num_rows($res) . ' users in db', true);
	$dbUsr = array();
	while ($row = mysqli_fetch_assoc($res)) {
		$dbUsr[$row['id']] = $row;
	}
	
	$qBL = "SELECT id FROM blacklist";
	$resBL = mysqli_query($db, $qBL);
	$html->addContent('found ' . mysqli_num_rows($resBL) . ' users in db blacklist', true);
	$dbBL = array();
	while ($row = mysqli_fetch_assoc($resBL)) {
		$dbBL[$row['id']] = $row;
	}
	//var_dump($dbBL);
	
	$html->addContent('merging zammad and db ' . date($dateformat), true);
	
	$added = 0;
	$removed = 0;
	$removedBL = 0;
	foreach ($users as $user) {
		if ($user->id == 933) {
			var_dump($user->id . $user->login);
			var_dump($user);
		}
		
		if (isset($dbUsr[$user->id])) {
			unset($dbUsr[$user->id]);
		} else {
			//new customer -> add to db			
			if ($user->infomail) {
				$infomail = 1;
			} else {
				$infomail = 0;
			}
			
			$q = "INSERT INTO customer VALUES (".$user->id.", '".mysqli_real_escape_string($db, $user->login)."', $user->active, $infomail)";
			$r = mysqli_query($db, $q);
			if ($r) {
				$added++;
			} else {
				$html->addContent($user->id . ': ' . mysqli_error($db), true);
				$html->addContent($q, true);
				$html->addContent('', true);
				$html->addContent('', true);
			}
		}
		//$html->addContent($user->id . ': ' . $user->login);	
	}
	
	if (count($dbUsr) > 0) {
		//remove old from db, not found in zammad
		foreach ($dbUsr as $dbid => $dbu) {
			
			$q = "DELETE FROM blacklist WHERE id = " . $dbid . " LIMIT 1";
			$r = mysqli_query($db, $q);
			if (mysqli_affected_rows($db) == 1) {
				$removedBL++;
			}
			
			$q = "DELETE FROM customer WHERE id = " . $dbid . " LIMIT 1";
			$r = mysqli_query($db, $q);
			if (mysqli_affected_rows($db) == 1) {
				$removed++;
			}
		}
		
	}
	$html->addContent('added ' . $added . ' into db', true);
	$html->addContent('removed ' . $removed . ' from db', true);
	$html->addContent('removed ' . $removedBL . ' from db blacklist', true);
	
} else {
	$html->addContent('Zammad connection problem!', true);
}
$html->addContent('finished sync ' . date($dateformat), true);

?>