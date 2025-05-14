<?php
    if(isset($_GET['login'])=='fail')
    {
        $message = "LOGIN FAILED" ;
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
    if(isset($_GET['verify'])=='fail')
    {
        $message = "ACCOUNT NOT VERIFIED" ;
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
    if(isset($_GET['notloggedin']))
    {
        $message = "Not logged in. Login to access the page!" ;
        echo "<script type='text/javascript'>alert('$message');</script>";
    }

    if(isset($_GET['register']) == 'success') // login success, start the session
    {
        $message = "REGISTER SUCCESS! ";
        $message .= "Your account has been created, please verify it by clicking the activation code";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>LOGIN</title>
        <?php
            include "header.inc.php";
        ?>
        
        
        <?php

         if(isset($_SESSION['uname']) ==true)
        {
            header('location:index.php?login=success');
        }
 
?>
    <body>
        <main class="bg-secondary">
            <div class="container-fluid col-md-6">
                <br>
                <div class="bg-dark" style="margin-top: 70px">
                    <h2 class="text-center" style="color:white; margin-top: 20px">Login</h2> 
                    <form name="logForm" action="process_login.php" method="POST" class="bg-dark pt-5 pb-5" id="login" onsubmit="return validateForm(this)">
                        <div class="text-center">
                            <label class="col-3 d-inline lead" style="color:white;" for="uname">Username:</label><br>
                            <input class="col-9 d-inline form-control form-control-sm" type="text" id="uname" name="uname" placeholder="Username"
                                   pattern="[A-Za-z0-9]{1,32}" title="Enter your username in alphabets or alphabets with numbers"><br>
                        </div> 
                        <div class="text-center">
                            <label class="col-3 d-inline lead" style="color:white;" for="password">Password: <br>
                            <input class="col-9 d-inline form-control form-control-sm" type="password" id="pwd" name="pwd"
                                   pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                   title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="Password"></label>
                        </div>
                        <div class="form-check mb-2 text-center">
                            <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                            <label class="form-check-label" for="remember-me" style="color: white;">Remember me</label>
                        </div>
                        <div class="form-group row justify-content-center">
                            <a href="forget_pw.php">Forget password?</a>
                        </div>
                        <div class="form-group justify-content-center row" id="signup">
                            <p style="color: white;">No account yet? 
                                <a href="register.php">Sign up</a> now!
                            </p>
                        </div>
                        <div class="justify-content-center row">
                            <input class="btn btn-primary btn-lg pl-5 pr-5" type="submit" id="submit" name="submit" value="Login">    
                        </div>
                    </form>
                    <br>
                </div>
            </div>
        </main>
    </body>
    <?php
        include "footer.inc.php";
    ?>
</html>
        

