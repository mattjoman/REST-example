<?php


$db_name	= 'my_website';
$db_user	= 'matt';
$db_password	= 'nice';
$db_host	= 'localhost';

$pdo = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_name, $db_user, $db_password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


# current day
$day = new DateTime('NOW');
# 7 days ago
$day->modify("-7 day");


$template = "DELETE FROM comments WHERE day_posted=?;";
$statement = $pdo->prepare($template);
$statement->bindValue(1, $day->format('d/m/Y'), PDO::PARAM_STR);
$statement->execute();

?>
