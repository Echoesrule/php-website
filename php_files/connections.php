<?php
$servername="localhost";
$username="root";
$password="";
$database="shopdb";
$conn=new mysqli($servername,$username,$password,$database);
if ($conn->connect_error){
    die(json_encode(['sucess'=>false,'error'=>'database connection failed']));
}
?>