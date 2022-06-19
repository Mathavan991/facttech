<?php
         $dbhost = 'localhost';
         $dbuser = 'root';
         $dbpass = '';
         $db='factura';
         $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
         $conn1 = new PDO("mysql:host=$dbhost;dbname=$db", $dbuser, $dbpass);
?>
