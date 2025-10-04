<?php
$servername="localhost";
$username="root";
$password="";
$dbname="shopdb";
$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
	print("connection failed:".$conn->connect_error);
}
else{
	echo"database connected successfully!";
}
?>