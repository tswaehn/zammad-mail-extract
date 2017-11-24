<?php 

$html->setTitle('sendmail');


//query alle an die eine mail gehen soll
$q = "SELECT c.*, b.id as bid FROM customer c left join blacklist b on b.id = c.id WHERE b.id is null AND c.infomail is true";
$res = mysqli_query($db, $q);


$html->addContent('sending mail to '.mysqli_num_rows($res).' customers:', true);
$html->addContent('', true);

while ($row = mysqli_fetch_assoc($res)) {

	$html->addContent(''.$row['id'].' - '.$row['login'].'', true);
}

?>