<?php
<?php

// Database configuration 
$dbHost     = "localhost";  //  your hostname
$dbUsername = "root";       //  your table username
$dbPassword = "";          // your table password
$dbName     = "codingstatus";  // your database name
 
// Create database connection 
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
 
// Check connection 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 


//include('database.php'); 

$searchTerm = $_GET['term'];
$sql = "SELECT * FROM tutorials WHERE tutorial_name LIKE '%".$searchTerm."%'"; 
$result = $conn->query($sql); 
if ($result->num_rows > 0) {
  $tutorialData = array(); 
  while($row = $result->fetch_assoc()) {

   $data['id']    = $row['id']; 
   $data['value'] = $row['tutorial_name'];
   array_push($tutorialData, $data);
} 
}
 echo json_encode($tutorialData);
?>