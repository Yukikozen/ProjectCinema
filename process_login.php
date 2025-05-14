<?php

include "config/config.php";

 function sanitize_input($data)
 {
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data; 
 }
 
 if(isset($_POST['submit']))
 {
     $success =true;
     $uname = $_POST['uname'];
     $pwd = $_POST['pwd'];
       
     $uname = sanitize_input($_POST["uname"]); 

               
      $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
      if($conn ->connect_error)
      {
           $errorMsg = "Connection failed: " . $conn->connect_error;     
           $success = false; 
      }
      else
      {
            $sql= $conn->prepare("SELECT uname, pwd, verify FROM memberinfo WHERE uname=?");
            $sql->bind_param('s', $uname);
            $sql->execute();
            $sql->store_result();
            $sql->bind_result($username, $hashed ,$verify);
            $sql->fetch();
            $pass = password_verify($pwd, $hashed); //check for password with hash
                if ($sql->num_rows > 0)
                {
                    $uname = $username; // retrieve username
                    if($pass)
                    {
                        $success = true;       // if password and user inputs are same
                    }
                    else
                    {
                        $success = false;
                        echo "<div style='margin-top: 100px'>"; 
                        echo "<h2> Oops! </h2>";
                        echo "<h4>The following input errors were detected:</h4>";
                        echo "Username not found or password doesn't match..."; 
                        echo "</div>";
                     
                    }
               }
            else     
            {   
                echo "<div style='margin-top: 100px'>"; 
                echo "<h2> Oops! </h2>";
                echo "<h4>The following input errors were detected:</h4>";
                echo "Username not found or password doesn't match...";         
                $success = false;     
            } 
        }  
        $sql->free_result();
        $conn->close();
  }
?>
<?php
if ($success) // if password and database passwords are match
{ 
    if($_POST["remember"]=='1' || $_POST["remember"]=='on') // check if user checks for remember me
    {
        $hour = time() + 3600 * 24 * 30;
        setcookie('uname', $uname, $hour);
        setcookie('pwd', $pwd, $hour);
    }
            if($verify == 1)
            {
               $success = true;
                session_start();  // start the session
                $_SESSION['uname']=$uname;
                header("Location:index.php?login=success");   
            }
                    
            else
            {
              $success = false;
              header("Location: login.php?verify=fail");
            }
   
}
 else // if fail remain on the same page
 {
     header("Location:login.php?login=fail"); 
 }
 ?>  