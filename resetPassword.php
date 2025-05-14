<!DOCTYPE html>
<html lang="en">
 
<?php
        include "header.inc.php";
    ?>
<?php
 if(isset($_SESSION['uname']) ==true)
{
   header('Location:index.php?login=success');
}
?>
<?php
$endtimereset = time();
 $success = false;
if (isset($_GET['email']) && (isset($_GET['hash'])))
{ //change password btn

        $email =$_GET['email'];
        echo $email;
        $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sqlhash = $conn->prepare("SELECT forgetExpire_time, uname FROM memberinfo WHERE email=?");
        $sqlhash ->bind_param('s', $email);
        $sqlhash ->execute();
        $sqlhash ->store_result();
        $sqlhash ->bind_result($time,$uname);
        $sqlhash ->fetch();
    if (($endtimereset - $time) < 600)
    {

        $success = true;
    }

else
{
    $success = false;
    $message = "Link expired";
     echo "<script type='text/javascript'>alert('$message');</script>";
}

}
else if (isset($_GET['update']))
{
    $message = "RESET PASSWORD FAILED due to wrong username" ;
    echo "<script type='text/javascript'>alert('$message');</script>";
    $success = true;
    $email =$_GET['email'];
}
else
{
    $success = false;
}
?>

    <head>
        <title>RESET PASSWORD</title>
        <link href="css/proreset.css" rel="stylesheet">
        <script>
    function validate()
    {
            var password = document.getElementById('newpwd').value;
            var confirmPassword = document.getElementById('cfmpwd').value;
            if (password != confirmPassword)
            {
                alert("Passwords do not match.");
                return false;
            }
            return true;
    } 
  </script>
    </head>
    <body>
<?php
  
    include "styles.inc.php";

?>

        <?php
        if($success)
        {
          
         ?>
        <div id="formstyle" style='margin-top: 100px; margin-left:30px'>
            <form action="process_reset.php?email=<?php $email =$_GET['email']; echo $email; ?>" method="POST" name="changepwd" id=changepwd onsubmit="return validate()">
        <h2 id="formtitle2">Change password</h2>
        <label class=label for="Username">Username:</label>
        <div class=text-left>
            <input type="text" id="uname"  class="col-9 d-inline form-control" name="uname" placeholder="Username" pattern="[A-Za-z0-9]{1,32}" title="Enter your username in alphabets or alphabets with numbers"><br>
        </div>
        <label class=label for="New password">New password:</label>
        <div class="text-left">
        <input type="password" name="newpwd" id="newpwd" class="col-9 d-inline form-control" form-control-sm pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
             placeholder="Enter new password">
        </div>
        <label class=label for="Confirm password">Confirm password:</label>
        <div class=text-left>
        <input type="password" name="cfmpwd" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
            title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
            id="cfmpwd"  class="col-9 d-inline form-control" form-control-sm placeholder="Enter new password again">
            <span id="message"></span><br><br>
        </div>

       <input type="submit" name="submit" id="submit" value="Change Password!">
        </form>
        </div>
        <?php
        }
        
        else
        {
            echo "<div style='margin-top: 100px'>"; 
            echo "<h3 style='color:red'> Oops! </h3>";
            echo "<h4>RESET PASSWORD LINK HAS EXPIRED</h4>";
            echo "<a href=forget_pw.php><button>Return forget Password Page</button></a>";
            echo "</div>";
       
        }
        
?>
    <?php
      include "footer.inc.php";
    ?>
    </body>
</html>