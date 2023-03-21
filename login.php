<?php
session_start();
$error = "";
if(array_key_exists("logout",$_GET)){
    session_unset();
    setcookie("id","",time()-60*60);
    $_COOKIE['id'] = "";
}else if(array_key_exists('id',$_SESSION) OR array_key_exists('id',$_COOKIE)){
    header('Location: loggedinpage.php');
}

include('connect.php');

if(array_key_exists('submit',$_POST)){
    if(!$_POST['email'] && !$_POST['password'] ){
        $error .= "Both email address and Password are required";
    }
    
    else if(!$_POST['email']){
        $error .="An email address is required ";
    }
    else if(!$_POST['password']){
        $error .="A password is required";
    }
    if($error != ''){
        $error = "<p>There were some errors</p>".$error;
    }else{
        $email = mysqli_real_escape_string($connection,$_POST['email']);
        if($_POST['SignUp'] == '1'){
            $query = "SELECT id FROM Users WHERE email='".$email."'LIMIT 1";
            $result = mysqli_query($connection,$query);
            if(mysqli_num_rows($result) > 0){
                $error.= "That email address has already been taken !";
            }else{
                $password = mysqli_real_escape_string($connection,$_POST['password']);
                $password = password_hash($password,PASSWORD_DEFAULT);
                $query = "INSERT INTO Users (email,password) VALUES('".$email."','".$password."')";
                if(mysqli_query($connection,$query)){
                    $id = mysqli_insert_id($connection);
                    $_SESSION['id'] = $id;
                    if(isset($_POST['stayLoggedIn'])){
                        setcookie('id',$id,time()+60*60*24*365);
                    }
                    header('Location: loggedinpage.php');
                }
                else{
                    $error .= "<p>Unable to sign up due to ".mysqli_error($connection)." error</p>.";
                }
            }
        }else{
            $query = "SELECT * FROM Users WHERE email='".$email."'";
            $result = mysqli_query($connection,$query);
            $row = mysqli_fetch_array($result);
            $password = mysqli_real_escape_string($connection,$_POST['password']);
            if(isset($row) AND array_key_exists('password',$row)){
                $passwordMatches = password_verify($password,$row['password']);
                if($passwordMatches){
                    $_SESSION['id'] = $row['id'];
                    if(isset($_POST['stayLoggedIn'])){
                        setcookie("id",$row['id'],time()+60*60*24*365);
                    }
                    header('Location: loggedinpage.php');
                }else{
                    $error = "The email/password combination could not be found";
                }
            }else{
                $error = "The email/password combination could not be found";

            }
        }

    }
}
?>

<?php include('L.html'); ?>


