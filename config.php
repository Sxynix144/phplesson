<?php
$host = "localhost";
$dbname= "restaurant";
$username = "root";
$password = "";


try{
    $restaurant = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $restaurant->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("Connection died: " . $e->getMessage());

}

?>