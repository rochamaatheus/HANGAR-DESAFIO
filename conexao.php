<?php 

$db_server = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'desafio-hangar';
$db_connection = '';

try {
  $db_connection = @mysqli_connect($db_server, $db_user, $db_password, $db_name);
  if (!$db_connection) {
    throw new Exception('Could not connect to MySQL server.');
  }
} catch (Exception $e) {
  echo $e->getMessage();
}
?>