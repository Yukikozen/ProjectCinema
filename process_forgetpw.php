<!DOCTYPE html>
<html lang="en">
    <head>
    <?php
        include "header.inc.php"; 
       
    ?>
        <?php

        if(isset($_SESSION['uname']) == true)
            {
                header('Location:index.php?login=success');
            }

        if(isset($_GET['valid']) == 'fail')
        {
            $message = "INVALID EMAIL OR/AND USERNAME";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    ?>
        <link href="css/proforgetpw.css" rel="stylesheet">
    </head>

<?php
 use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;
            use PHPMailer\PHPMailer\SMTP;

            require './PHPMailer/src/Exception.php';
            require './PHPMailer/src/PHPMailer.php';
            require './PHPMailer/src/SMTP.php';

 function sanitize_input($data)
 {
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data; 
 }
 
 $forgettime = time();
 $forgettime1 = $forgettime;
// echo $forgettime;
 if(isset($_POST['lostpw']))
 {
    $success =true;
    $uname = $_POST['uname'];
    $email= $_POST['email'];

    $uname = sanitize_input($_POST["uname"]); 
    $email = sanitize_input($_POST["email"]); 
               
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    if($conn ->connect_error)
    {
        $errorMsg = "Connection failed: " . $conn->connect_error;     
        $success = false; 
    }
    else
    {
        $sql = $conn->prepare("SELECT UserId FROM memberinfo WHERE uname =? && email =?");
        $sql ->bind_param('ss', $uname, $email);
        $sql ->execute();
        $sql ->store_result();
        $sql ->bind_result($Id);
        $sql ->fetch();
      
        if ($sql->num_rows > 0)
        {
            $sqlforget = $conn->prepare("UPDATE memberinfo SET forgetExpire_time=? WHERE uname=?");
            $sqlforget ->bind_param('ss',$forgettime1,$uname);
            $sqlforget->execute();
            $sqlforget ->store_result();
            $success = true;
        }
        else
        {
            $success = false;
            header("Location: forget_pw.php?valid=fail");
        }
      
    }
               
    $conn->close();
}  

else     
    {   
        echo "<div style='margin-top: 100px'>"; 
        echo "<h2> Oops! </h2>";
        echo "<h3>The following input errors were detected:</h3>";    
        echo "Username not found or email doesn't match...";         
        echo "</div>";
        $success = false;     
        
    }    
?>


    <body>
<?php
if ($success)
{ 
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    $sql = $conn->prepare("SELECT UserId, uname, email FROM memberinfo WHERE uname=? && email=?");
    $sql ->bind_param('ss', $uname, $email);
    $sql ->execute();
    $sql ->store_result();
    $sql ->bind_result($Id, $uname, $email);
    $sql ->fetch();
  
    $hash_pw = md5( rand(0,1000) );
  
    $mail = new PHPMailer();
    $mail->CharSet =  "utf-8";
    $mail->IsSMTP();
    $mail->Host = SMTPSSL;
    // enable SMTP authentication

    $mail->SMTPAuth = true;                  
    // GMAIL username
    $mail->Username = SMTPUSER;
    // GMAIL password
    $mail->Password = SMTPPASS;
    $mail->SMTPSecure = SMTPSEC;  
    // sets GMAIL as the SMTP server
    $mail->Host = SMTPHOST;
    // set the SMTP port for the GMAIL server
    $mail->Port = SMTPPORT;
    $mail->From= SMTPFROM;
    $mail->FromName=SMTPFROMNAME;
    $mail->AddAddress($email, $uname);
    $mail->Subject  = 'Reset your password for PV account';
    $mail->IsHTML(true);

    $msg = "<p>We received a password reset request. The link to reset your password is below if you did not make this request, you can ignore the email</p><br>
        
				------------------------<br>
				Username: $uname    <br>
				------------------------<br>
				 
				Please click this link to reset your account password:<br>
                                <a href =http://localhost/ProjectCinema/resetPassword.php?Id=$Id&email=$email&hash=$hash_pw>http://localhost/ProjectCinema/resetPassword.php?Id=$Id&email=$email&hash=$hash_pw</a>";
                            
    $mail->Body = $msg;
    if($mail->Send())
    {   
	echo "<div style='margin-top: 100px'>";
        echo "<h4>Email Verification</h4>";
        echo "Message was Successfully Send :) <br>"; 
        echo "<a href=index.php><button>Return to Home</button></a>";
	echo "</div>";
    }
    else
    {
        echo "<div style='margin-top: 100px'>"; 
        echo "<h2> Oops! </h2>";
        echo "<h4>The following input errors were detected:</h4>";
        echo "Mail Error - >".$mail->ErrorInfo;
        echo "</div>";
    }
}
?>
    </body>
    <?php
        include 'footer.inc.php';
    ?>
</html>