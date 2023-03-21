<?php
session_start();
if(array_key_exists('content',$_POST)){
    include('connect.php');
    $query = "UPDATE Users SET diary='".mysqli_real_escape_string($connection,$_POST['content'])."' WHERE id=".mysqli_real_escape_string($connection,$_SESSION['id'])." LIMIT 1";
    mysqli_query($connection,$query);
}
?>