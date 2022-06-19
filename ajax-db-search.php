<?php
try {
include 'db_config.php';
$pdo = new PDO("mysql:host=$dbhost;dbname=$db",  $dbuser, $dbpass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
  );
} catch (Exception $ex) { die($ex->getMessage()); }
$data = [];
$stmt = $pdo->prepare("SELECT `product_name` FROM `product_details` WHERE `product_name` LIKE ?");
$stmt->execute(["%" . $_POST["search"] . "%"]);
while ($row = $stmt->fetch()) { $data[] = $row["product_name"]; }
echo count($data)==0 ? "null" : json_encode($data) ;
?>