<?php
include "connection.php";

if($_SERVER['REQUEST_METHOD']=="POST")
{

    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $password = md5($password);
    
    $query = "SELECT * FROM customer WHERE phone_number='$phone_number' and password = '$password'";
    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result)>0)
    {
        $row = mysqli_fetch_assoc($result);

        session_start();
        $_SESSION['customer_id']= $row['customer_id'];
        
        
        header("location:index.php");
        exit();
    }
    else
    {
        echo "Invalid phone number or password ! Please Try Again";
    }

}


?>