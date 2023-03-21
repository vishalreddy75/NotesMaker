<?php
session_start();
$diaryContent = "";
if(array_key_exists('id',$_COOKIE)){
    $_SESSION['id'] = $_COOKIE['id'];
}
if(array_key_exists('id',$_SESSION)){
    include('connect.php');
    $query = "SELECT diary FROM Users WHERE id =".mysqli_real_escape_string($connection,$_SESSION['id'])." LIMIT 1";
    $result = mysqli_query($connection,$query);
    $row = mysqli_fetch_array($result);
    $diaryContent = $row['diary'];
}else{
    header('Location: login.php');
}
?>
<?php include('Lgd.html'); ?>