<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

function saveMemberToDB()
{
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $pwd = $_POST['pwd'];
    $hashed_password = password_hash($pwd, PASSWORD_DEFAULT);
    $hash = md5( rand(0,1000) ); // Generate random 32 character hash and assign it to a local variable.
    $success =true;
    
    $sql= $conn->prepare("SELECT uname FROM memberinfo WHERE uname = ?");
    $sql->bind_param('s', $uname);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($unameDB);
    $sql->fetch();
    
   
    $userfound = $sql->num_rows;
    if($userfound >=1 )  //check for username existing
    {
        header("Location:register.php?register=fail");
        $success = false;
    }
    else
    {
        $sqlemail = $conn->prepare("SELECT email FROM memberinfo WHERE email=?");
        $sql->bind_param('s', $email);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($emailDB);
        $sql->fetch();   
        
  
        $emailfound = $sqlemail->num_rows;
        if($emailfound > 0)
        {
            header("Location:register.php?register=fail");
            $success = false;
        }
        else
        {
            $time1 = time(); //declaring the current time being created account in seconds
            $time= $time1;

            $sql_insert = $conn->prepare("INSERT INTO memberinfo (uname, email, pwd, gender, phone, profilepic, hash,expiry_time) VALUES (?,?,?,?,?,?,?,?)");
            $a = '';
            $sql_insert->bind_param('ssssisss', $uname, $email, $hashed_password, $gender, $phone, $a ,$hash, $time);
            $result = $sql_insert->execute();
            
            if ($result) //check for the registration success.. if success go over to login page
            {
                header("Location:login.php?register=success");
                $success = true;
            }
            else
            {
                echo "Register failed";
            }
        }
        mysqli_close($conn);
        if ($success){
            return 1;
        }
        else{
            return 0;
        }
    }
}

 //}//Helper function that checks input for malicious or unwanted content.
 function sanitize_input($data)
 {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
 }

if(isset($_POST['submit']))
{
    $email = $errorMsg = "";
    $uname = $errorMsg = "";
    $gender;
    $pwd = $_POST["pwd"] ;
    $cfmpwd = $_POST["cfmpwd"];
    $success = true;

    if(empty($_POST["uname"]))
    {
        $errorMsg .= "Username is required.<br>";
        $success = false;
    }
    if($pwd != $cfmpwd)
    {
        $errorMsg .= "Password doesn't match.<br>";
        $success = false;
    }

     if(empty($_POST["gender"]))
    {
        $errorMsg .= "Gender is required.<br>";
        $success = false;
    }

    if (empty($_POST["email"]))
    {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    }
    else
    {
        $email = sanitize_input($_POST["email"]);// Additional check to make sure e-mail address is well-formed.
        $uname = sanitize_input($_POST["uname"]);
       

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $errorMsg .= "Invalid email format.";
            $success = false;
        }
    }
?>
    <html lang="en">
       <head>
            <title>PLATINUM VILLAGE</title>
            <link rel="stylesheet" href="css/process_reg.css">
            <?php
                include "header.inc.php";
              
            ?>
            <?php

            

            if(isset($_GET['valid']) == 'fail')
            {
                $message = "INVALID EMAIL AND USERNAME";
                echo "<script type='text/javascript'>alert('$message');</script>";
            }
             if(isset($_SESSION['uname']) == true)
            {
                header('Location:index.php?login=success');
            }
            ?>
        </head>
    <body>
   
<?php
    if($success)
    {
        if (saveMemberToDB() == 1)
        {
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
            $sql= $conn->prepare("SELECT UserId, uname, email, pwd, hash FROM memberinfo WHERE uname=? &&email=?");
      
            $sql->bind_param('ss', $uname,$email);
            $sql->execute();
            $sql->store_result();
            $sql->bind_result($Id, $uname, $email, $passwd, $hash);
            $sql->fetch();
           

            $mail = new PHPMailer();
            $mail->CharSet = "utf-8";
            $mail->IsSMTP();
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
            $mail->FromName= SMTPFROMNAME;
            $mail->addAddress($email, $uname);
            $mail->Subject  = 'Account has been created';
            $mail->IsHTML(true);
            $msg = "Thanks for signing up! Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below. <br>
                                    ------------------------ <br>
                                    Username: $uname <br>
                                    Password: $pwd <br>
                                    ------------------------ <br>

                                    Please click this link to activate your account:
                                    <a href =http://localhost/ProjectCinema/verify.php?Id=$Id&hash=$hash>http://localhost/ProjectCinema/verify.php?Id=$Id&hash=$hash</a>";

//                                    <a href =http://47.74.213.30/AY19/P3-2/Project2/verify.php?Id=$Id&hash=$hash>http://47.74.213.30/AY19/P3-2/Project2/verify.php?Id=$Id&hash=$hash</a>";

            $mail->Body = $msg;
            if($mail->Send())
            {
           	echo "<div style='margin-top: 100px'>"; 
		echo "<h4>Your registration successful!</h4>";
                echo "Message was Successfully Send :)";
                echo "<p>Thank you for signing up, " .$uname;
                echo "<br>";
                echo "<a href=login.php><button>Log-in</button></a>";
                echo "&nbsp; &nbsp; &nbsp;";
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
    }  
    else
    {
	echo "<div style='margin-top: 100px'>"; 
        echo "<h2> Oops! </h2>";
        echo "<h4>The following input errors were detected:</h4>";
        echo "<p>" . $errorMsg . "</p>";
        echo "<a href=register.php><button>Return to Sign Up</button></a>";
	echo "</div>";
    }

 }


    ?>
    <?php
        include "footer.inc.php";
    ?>
    </body>
</html>