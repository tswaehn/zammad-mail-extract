<?php

$html->setTitle('Main Menu');
$html->addContent('Zammad Mailer - work in progress', true);

$html->addContent('', true);
$html->addContent('<a href="index.php?action=listuser">user list</a>', true);
$html->addContent('', true);
$html->addContent('<a href="index.php?action=syncuser">sync user database</a>', true);
$html->addContent('<a href="index.php?action=sendmail">send mail</a>', true);
?>