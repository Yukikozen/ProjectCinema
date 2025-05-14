<!DOCTYPE HTML>
<html lang="en">
    <head>
        <link href="css/proreset.css" rel="stylesheet">
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
            $message = "INVALID EMAIL AND USERNAME";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
        
        
    ?>
    </head>

    <body>
        <main>
            <?php
            
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;
            use PHPMailer\PHPMailer\SMTP;

            require './PHPMailer/src/Exception.php';
            require './PHPMailer/src/PHPMailer.php';
            require './PHPMailer/src/SMTP.php';

            $email = $_GET['email'];
          
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        
            if (isset($_POST['submit']))
            { //change password btn
                $newpwd = $_POST['newpwd'];
                $hashed_password2 = password_hash($newpwd, PASSWORD_DEFAULT);
                $cfmpwd = $_POST['cfmpwd'];
                $uname = $_POST['uname'];
               
                $sql = $conn->prepare("SELECT uname,email FROM memberinfo WHERE uname=? and email=?");
                $sql->bind_param('ss', $uname, $email);
                $member_list = $sql->execute();
                $sql->store_result();
                $sql->bind_result($uname, $email);
                $sql->fetch();
                $success = true;
               
                if(empty($uname))
                {
                    $success = false;
                }
               
        
                if ($member_list)
                {
                    
                    if (empty($_POST['newpwd']) || empty($_POST['cfmpwd'])) { // check for the user inputs are empty
                        $message = "Password not set!";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                        
                    }
                    else if ((isset($_POST['newpwd']) && isset($_POST['cfmpwd']))) 
                    {
                       
                        if ($newpwd != $cfmpwd)
                        { //if user inputs passwords are not match
                            $message = "Passwords do not match!";
                            echo "<script type='text/javascript'>alert('$message');</script>";
                            $success = false;
                            header("Location: resetPassword.php?retry=fail");
                        }
                        else
                        { // if success, update the the newpwd in hashed formats
                         
                        
                        if ($success)
                        {
                                $hashed_password2 = password_hash($newpwd, PASSWORD_DEFAULT);
                                $sql = $conn->prepare("UPDATE memberinfo SET pwd=? WHERE uname=?");
                                $sql->bind_param('ss', $hashed_password2, $uname);
                                $result = $sql->execute();
                                

                                if ($result) {
                                    $message = "Password Change Successful";
                                    echo "<script type='text/javascript'>alert('$message');</script>";

                                    $mail = new PHPMailer();
                                    $mail->CharSet = "utf-8";
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
                                    $mail->From = SMTPFROM;
                                    $mail->FromName = SMTPFROMNAME;
                                    $mail->AddAddress($email, $uname);
                                    $mail->Subject = 'Password Changed';
                                    $mail->IsHTML(true);

                                    $msg = "<p>Your password has been changed.</p> <br>
        
				------------------------ <br>
				Username: $uname <br>
                                Password: $newpwd<br>
				------------------------ <br>
				 
				Please click this link to login your PV account account:
                                <a href =http://localhost/ProjectCinema/login.php>http://localhost/ProjectCinema/login.php</a>";


                                    $mail->Body = $msg;
                                    if ($mail->Send()) {
					echo "<div style='margin-top: 100px;'>";
                                        echo "<h4> Password has been reset.</h4>";
                                        echo "Message was Successfully Send :) <br>";
                                        echo "<a href=login.php><button>Log-in</button></a>";
                                        echo "&nbsp; &nbsp; &nbsp;";
                                        echo "<a href=index.php><button>Return to Home</button></a>";
					echo "</div>";
                                    }
                                } else
                                {
                                     $message = "Password Change UNSUCCESSFUL!";
                                    echo "<div style='margin-top: 100px'>"; 
                                    echo "<h2> Oops! </h2>";
                                    echo "<h4>The following input errors were detected:</h4>";
                                    echo "<script type='text/javascript'>alert('$message');</script>";
                                    echo "Mail Error - >" . $mail->ErrorInfo;
                                    echo "</div>";
                                }
                            }
                            else
                            {// if there is no records of username
                                 $email = $_GET['email'];
                                header("Location: resetPassword.php?update=fail&email=$email");
                            }
                        }
                    }
                }
            
            }
            

            mysqli_close($conn);
            ?>
        </main>
        <?php
            include "footer.inc.php";
        ?>
    </body>
</html>