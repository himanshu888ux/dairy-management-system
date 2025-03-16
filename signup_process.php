<?php
include "connection.php";

if($_SERVER['REQUEST_METHOD']=="POST")
{

   $fname = $_POST['fname'];
   $lname = $_POST['lname'];
   $phone = $_POST['phone'];
   $email = $_POST['email'];
   $address = $_POST['address'];
   $password = $_POST['password'];
   $cnf_password = $_POST['cnf-password'];

   $q = "SELECT * from customer where phone_number='$phone'";
   $res = mysqli_query($conn,$q);

   if(mysqli_num_rows($res)>0)
   {
         echo "<script> alert('User Already Exists ! Please login '); 
               window.location.href='login.php';
         </script>";
         exit();
   }
   else{

   # Validate input data

   $errors_array = array();
   if(!preg_match("/^[a-zA-Z ]*$/",$fname))
   {
      $errors_array[] = "Invalid First Name ! Please Enter Proper First Name ";
   }
   if(!preg_match("/^[a-zA-Z ]*$/",$lname))
   {
      $errors_array[] = "Invalid Last Name ! Please Enter Proper Last Name ";
   }

   if(!preg_match("/^[0-9]{10,15}$/",$phone))
   {
      $errors_array[] = "Invalid Phone Number ! Please Enter Proper Phone Number ";
   }

   if(!filter_var($email,FILTER_VALIDATE_EMAIL))
   {
      $errors_array[] = "Invalid Email ! Please Enter Proper Email ";
   }

   if(strlen($password)<8)
   {
      $errors_array[] = "Please Enter proper 8 character's password";
   }

   if($password != $cnf_password)
   {
      $errors_array[] = "Password does not match with confirm password ! please enter 
                        same password in both field";
   }
   if(!empty($errors_array))
   {
         foreach($errors_array as $errors)
         {
            echo $errors."<br>";
         }
         exit();

   }


   $password = md5($password);


   $query = "INSERT INTO customer (`first_name`,`last_name`,`phone_number`,`email`,`address`,`password`) VALUES (?,?,?,?,?,?);";

   $statement = mysqli_prepare($conn,$query);
   mysqli_stmt_bind_param($statement,"ssssss",$fname,$lname,$phone,$email,$address,$password);
   mysqli_stmt_execute($statement);


   if(mysqli_stmt_affected_rows($statement)>0)
   {
      echo "<script> alert('Sign up Successfully ');  </script>";
      header("location:login.php");
      exit();
   }
   else
   {
      echo "<script> alert('Sign Up Failed ');  </script>";
   }

}
}

?>
