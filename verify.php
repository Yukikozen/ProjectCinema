<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

?>
 
<html lang="en">
<head>
    <title>SIGNUP VERIFICATION</title>
    <link href="css/valid.css" rel="stylesheet">
    <?php
        include "header.inc.php";
    ?>
    <?php
//session_start();
  if(isset($_SESSION['uname']) == true)
            {
                header('Location:index.php?login=success');
            }

if(isset($_GET['valid']) == 'fail')
        {
            $message = "INVALID EMAIL AND USERNAME";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    ?>
</head>
<body>
    <?php
    $endtime = time();
    if(!empty($_GET['hash']) && isset($_GET['hash']))
    {
        $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $hash = $_GET['hash'];
        $sqlhash = $conn->prepare("SELECT uname, email, expiry_time, verify FROM memberinfo WHERE hash=?");
        //$a=0;
        $sqlhash ->bind_param('s', $hash);
        $sqlhash ->execute();
        $sqlhash ->store_result();
        $sqlhash ->bind_result($uname, $email,$time, $a);
        $sqlhash ->fetch();

        if($endtime - $time < 600)
        {
            
            $success = true;
             if ($sqlhash ->num_rows >0) //check for username existing
             {
                $success = true; 
                $validate_active = $conn->prepare("UPDATE memberinfo SET verify=? WHERE hash=?");
            $a=1;
            $validate_active->bind_param('is', $a, $hash);
            $result = $validate_active->execute();
            
            //$result = mysqli_query($conn, $validate_active);
            if($result)
            {
                $message = "Activation SUCCESSFUL!";
                echo "<div style='margin-top: 100px;'>";
                echo "<script type='text/javascript'>alert('$message');</script>";
                echo "<h4>Activation SUCCESSFUL!</h4>";
                echo "<p>You may now login with your PV account.<p>";
                echo "<br>";
                echo "<a href=login.php><button>Log-in</button></a>";
                echo "&nbsp; &nbsp; &nbsp;";
                echo "<a href=index.php><button>Return to Home</button></a>";
                echo "</div>";
            }
            else
            {
                $message = "Activation UNSUCCESSFUL!";
                echo "<script type='text/javascript'>alert('$message');</script>";
            }
             }
           
        }
        
     
        else
        {
            echo $a;
            if($a ==1) //if the account has been activated
            {
                $success = true;
                 echo "<div style='margin-top: 100px'>";
                echo "<h4>Account has already been activated!</h4>";
                echo "<p>You may now login with your PV account.<p>";
                echo "<br><br>";
                echo "<a href=login.php><button>Log-in</button></a>";
                echo "&nbsp; &nbsp; &nbsp;";
                echo "<a href=index.php><button>Return to Home</button></a>";
		echo "</div>";
                
            }
            else
            {
               
                $success = false;
                $delete = $conn->prepare("DELETE FROM memberinfo WHERE email=?" );
                $delete ->bind_param('s', $email);
                $result = $delete->execute();
            
                $message = "Activation link has expired!";
                echo "<script type='text/javascript'>alert('$message');</script>";
                echo "<div style='margin-top: 100px'>"; 
                echo "<h4>ACTIVATION LINK HAS EXPIRED!</h4>";
                echo "<p>Please register again!";
                echo "<br><br>";             
                echo "&nbsp;";
                echo "<a href=register.php><button>Return to SignUp</button></a>";
                echo "</div>";
            }
        }
        
       
    }

    else
    {
         echo "<div style='margin-top: 100px'>";
         echo '<h1>Something went wrong</h1>';
         echo "</div>";
    }   

     ?>
</body>

<?php include 'footer.inc.php'; ?>
</html>