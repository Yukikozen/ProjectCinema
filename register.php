<!DOCTYPE html>
<html lang="en">
    <head>
        <title>SIGN UP</title>
        <link rel="stylesheet" href="css/signup_css.css">
        <script src="js/regvalidate.js" type="text/javascript"></script>
    </head>
        <body>
            <?php
                include "header.inc.php";
                //*********************************LOGIC IMPLEMENTAION***************************************
                if(isset($_GET['register']) == 'fail')
                {
                    $message = "EMAIL OR USERNAME ALREADY EXIST" ;
                    echo "<script type='text/javascript'>alert('$message');</script>";
                }
            ?>
            
  <?php

  if(isset($_SESSION['uname']) ==true)
  {
        header('Location:index.php?login=success');
  }
 
?>
        <div id="Header"></div>

        <main class="bg-secondary">
            <div class="container-fluid col-md-6 ">
                <div class="container"><br/></div>
                <form class="register bg-dark" id="regForm" method="POST" action="process_register.php"  onsubmit="return callOut(this)">
                   <h1>Sign Up</h1>
                    
                    <div class="text-center">
                        <label for="email-register">Email:<br>
                        <input type="email" style="width:500px" id="email-register" name="email" placeholder="Email" title="Please give a valid email e.g. abc@gmail.com" required>
                        </label><br> 
                    </div>

                    <div class="text-center">
                        <label for="Username">Username:<br>
                        <input type="text" style="width:500px" id="uname" name="uname" placeholder="Username" pattern="[A-Za-z0-9]{1,32}" title="Enter your username in alphabets or alphabets with numbers">
                        </label><br>
                    </div>

                    <div class="text-center">
                        <label for="password">Password:<br>
                        <input type="password" style="width:500px" id="pwd" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                               title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                               name="pwd" placeholder="Password" required
                        </label>><br>
                    </div>

                    <div class="text-center">
                        <label for="password-confirm">Confirm Password:<br>
                        <input type="password" style="width:500px" id="cfmpwd" name="cfmpwd"
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                               title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                               placeholder="Confirm password" required>
                        </label><br>           
                    </div>

                    <div class="text-center">
                        <label for="phone-number">Phone Number:<br>
                        <input type="tel" style="width:500px" name="phone" placeholder="Phone Number" required pattern="[89][0-9]{7}" title="phone number starts with 8 or 9 and must be 8 numbers">
                        </label><br>
                    </div>
                    <div class="text-center">
                        <label for="Gender">Gender:</label> &nbsp;
                        <label><input type="radio" class="gender" name="gender" id="gender_Male" value="Male"> Male &nbsp;
                        <input type="radio" class="gender" name="gender" id="gender_Female" value="Female"> Female<br>
                        </label><br>
                        
                    </div>
                    <div class="text-center">
                        <p class="check-mark">
                            <input type="checkbox" id="accept-terms" required>
                            <label for="accept-terms" style="color: white;">I agree to the <a target="_blank" href="Terms&Conditions.php">Terms & Conditions</a></label>
                        </p>
                    </div>
                    <input type="submit" name="submit" id="submit" value="Create Account"><br>
                    <input id ="reset" type="reset" value="Reset">
                </form>
                <div class="container"><br/></div>
            </div>
        </main>

        <?php
            include "footer.inc.php";
        ?>
    </body>
    
</html>
