<?php
$doc_root = $_SERVER['DOCUMENT_ROOT'];
require_once "$doc_root/camagru/config/database.php";
function  connect_db()
{
  global $DB_DSN, $DB_USER, $DB_PASSWORD;

  try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOException $ex){
    return false;
  }
  return ($pdo);
}
?>