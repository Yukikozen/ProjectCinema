<!DOCTYPE html>
<html>
    <head>
        <title>FORGET PASSWORD</title>
        <link rel="stylesheet" href="css/forgetpw_css.css">
        <?php
            include "header.inc.php";
        ?>
        
        <?php
         if(isset($_SESSION['uname']) ==true)
        {
            header('Location:index.php?login=success');
        }
 

        if(isset($_GET['valid']) == 'fail')
        {
            $message = "INVALID EMAIL OR/AND USERNAME";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    ?>
    </head>
    <body>
        <main class="bg-secondary" style="margin-bottom: 30px">
            <br>
            <div class="container-fluid col-md-9 bg-dark" style="margin-top: 70px;">                
                <h1>Forget Password</h1>
<!--                 <p> An e-mail will be send to you with instructions on how to reset your password.</p>-->
                <form class="offset-md-3 col-md-6" id="forgot" action="process_forgetpw.php" method="POST" name="forgotform">
                    <div style="text-align:center;">
                        <label class="col-3 d-inline lead" for="Uname">Username:<br>
                        <input class="col-9 d-inline form-control form-control-sm" type="text" id="uname" name="uname" placeholder="Username"><br>
                        </label>
                        <label class="col-3 d-inline lead" for="email-register">Email:<br>
                        <input class="col-9 d-inline form-control form-control-sm" type="email" id="email-register" name="email" required placeholder="Email"><br>
                        </label>
                        <br>
                        <input class="btn btn-primary" style="margin-bottom:3em;" name="lostpw" type="submit" value="Recover Password">
                    </div>
                </form>
            </div>
            <br>         
        </main>
        <?php
            include "footer.inc.php";
        ?>
    </body>

</html>


