<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>EDIT PROFILE</title>
        <link href="css/account.css" rel="stylesheet">
        <?php
            include "header.inc.php";
            function sanitize_input($data)
           {
              $data = trim($data);
              $data = stripslashes($data);
              $data = htmlspecialchars($data);
              return $data;
           }

        ?>
        <script>
        function validate()
        {
            var current = document.getElementById('currentpwd').value;
            var password = document.getElementById('newpwd').value;
            var confirmPassword = document.getElementById('cfmpwd').value;
            if (current != "")
            {
                if(password == "" || confirmPassword == "")
                {
                    alert("Kindly provide new or confirm password");
                    return false;
                  
                }
                else if (password != confirmPassword)
                {
                    alert("Passwords do not match.");
                    return false;
               
                }
                else
                {
//                    alert("Kindly provide the current password");
//                    return false;
                }
            }
             else if (password != "")
            {
                if(current == "")
                {
                    alert("Kindly provide current password field");
                    return false;
                  
                }
                else if (confirmPassword == "")
                    {
                    alert("Kindly provide confirm password field");
                    return false;
                  
                }
                else if (password != confirmPassword)
                {
                    alert("Passwords do not match.");
                    return false;
               
                }
                
            }
            
            else if (confirmPassword != "")
            {
                if(current == "")
                {
                    alert("Kindly provide current password field");
                    return false;
                  
                }
                else if (password == "")
                    {
                    alert("Kindly provide new password field");
                    return false;
                  
                }
                else if (password != confirmPassword)
                {
                    alert("Passwords do not match.");
                    return false;
               
                }
                
            }
            else
                {

                }
        return true;
    }
    
  </script>
    </head>

<?php
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
$success = true;
if (isset($_SESSION['uname']))
{
    $uname = $_SESSION['uname'];
    
    $sql = $conn->prepare("SELECT pwd, phone FROM memberinfo where uname=?");
    $sql->bind_param('s', $uname);
    $member_list = $sql->execute();
    $sql->store_result();
    $sql->bind_result($pwd2,$phone);
    $sql->fetch();

}
else
{
    header("Location:login.php?notloggedin=true");
}

 if (isset($_POST['submit'])) { //change password btn
     
    $pwd = $_POST['pwd'];
    if((!empty($pwd))){
    $hashed_password = password_hash($pwd, PASSWORD_DEFAULT);
    }
    $newpwd = $_POST['newpwd']; 
    if((!empty($newpwd))){
    $hashed_password2= password_hash($newpwd, PASSWORD_DEFAULT);
    }
    $cfmpwd = $_POST['cfmpwd']; 
    $uname = $_SESSION['uname'];
    if(!empty($pwd) && (!empty($newpwd)) && (!empty($cfmpwd)) && (!empty($pwd2)) && (!empty($hashed_password)) && (!empty($hashed_password2)) && (!isset($hashed_password)))
    {
             $alldeclared = $true;
    }
    else{$alldeclared = false;}
    if($alldeclared = true)
    {

        if ($member_list)
        {
            if((empty($_POST['newpwd'])) && (empty($_POST['cfmpwd'])) && (empty($_POST['pwd'])) && (!isset($_POST['newpwd'])) && (!isset($_POST['cfmpwd'])) && (!isset($_POST['pwd'])) )
            {
                $success = false;

            }
        
        else
           {

            if ((!empty($_POST['newpwd'])) && (!empty($_POST['cfmpwd'])) &&(!empty($_POST['pwd'])))
            {
             
                $hashed_password = password_hash($pwd, PASSWORD_DEFAULT);
                if(password_verify($hashed_password, $pwd2))
                {
                    $success = false;

                }
                $pass = password_verify($pwd,$hashed_password); //hash the user input password and compare

                    if ($hashed_password == $hashed_password2)
                    {   
                        $success = false;
                        $message = "Password must be different from previous";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                    }
            
                    else if($pwd == $newpwd)
                    {

                        $success = false;
                        $message = "Password must be different from previous";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                    }
                     else if ($newpwd != $cfmpwd)
                    {
                        $success = false;

                    }
                    else
                    {
                        if($success)
                        {
                            
                            $sql = $conn->prepare("UPDATE memberinfo SET pwd=? WHERE uname=?");
                            $sql->bind_param('ss',$hashed_password2,$uname);
                            $result = $sql->execute();

                            if ($result)
                            {
                                $message = "Password Change Successful";
                                echo "<script type='text/javascript'>alert('$message');</script>";
                            }
                            else
                            {
                                $success = false;
                                $message = "Password Change UNSUCCESSFUL";
                                echo "<script type='text/javascript'>alert('$message');</script>";
                            }
                        }
                    }
                }
            }
     
 
            
        
        
              
            }
        
      
         if (isset($_POST['phone'])&& (!empty($_POST['phone']))) //changing phone number
        {
             $phone = sanitize_input($_POST["phone"]);
            $phone = $_POST['phone'];
            $uname = $_SESSION['uname'];
            
             $sqlphone = $conn->prepare("UPDATE memberinfo SET phone=? WHERE uname=?");
             $sqlphone->bind_param('is',$phone,$uname);
             $result = $sqlphone->execute();
            
            
           
            if ($result)
            {
                $success = true;
                $message = "Phone no. change Successful!";
                echo "<script type='text/javascript'>alert('$message');</script>";
            }
            else
            {
                $success = false;
                $message = "Phone no. change Unsuccessful!";
                echo "<script type='text/javascript'>alert('$message');</script>";
            }
        }
    }}
 
mysqli_close($conn);

?>

    <body>
        <main> 
            <section class="bg-secondary">
            <?php if (isset($_SESSION['uname'])) { ?>
            <!--  if the session is active -->
                <form action="upload_validate.php" method="POST" enctype="multipart/form-data" name="editprofile" id="editprofile">
                    <h2 id="maintitle" title="try the cool effect">Update Particulars</h2>
                    <h3 class="formtitle">Profile Picture</h3>
                    <p class="tooltip" title="Optional">Upload your profile picture: 
                        <span class="tooltiptext">Max allowed file size: 1MB; Format: -jpg, -jpeg, -png</span>
                    </p> &nbsp;
                    <!--  if the session is active, retrieving user's profile picture -->
                    <a href="account.php"><img id="profilepic2" alt="picture" src="<?php
                        if ($one_member['profilepic'] != "")
                        {
                            echo $one_member['profilepic'];

                        }
                        else if (isset($_SESSION['uname']) && empty($one_member['profilepic']))
                        {
                                echo "images/account/unknown-profile.png";

                        }?>"></a>
                      <br><br>
                      <label><input type="file" name="fileToUpload" id="fileToUpload" required><br><br>
                      <input type="submit" name="button" id="button" value="Upload profile!"></label>
                    <span id="promptmessage">
                        <?php
                        if (isset($_GET['uploadfail'])) {
                            echo "<b>" . "Upload failed. Invalid file type" . "</b>";
                        }
                         if (isset($_GET['uploadfail2'])) {
                            echo "<b>" . "Upload failed. File size too large" . "</b>";
                        }
                        ?>
                    </span>
                    <br><br>
                </form>
            
          
            <form action="account.php" method="POST" name="changepwd" id="changepwd" onsubmit="validate()">
                    <h3 id="formtitle2">Change password</h3>
                    <p><b>Username: &nbsp; &nbsp; &nbsp;<?php echo $one_member['uname']; ?></b></p>
                    <p><b>Email: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $one_member['email']; ?></b></p>
                    <p><b>Gender: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $one_member['gender']; ?></b></p>

                    <label class="lab" for="Current password">Current password:
                        <div class="text-left">
                        <input type="password" id="currentpwd" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                      title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" name="pwd" class="col-9 d-inline form-control form-control-sm">
                    
                    </div>
                    </label>
                

                    <label class="lab" for="New password">New password:
                     <div class="text-left">
                        <input type="password" name="newpwd" id="newpwd" class="col-9 d-inline form-control form-control-sm" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" >
                    </div>
                    </label>
                   
                    <label class="lab" for="Confirm password">Confirm password:
                         <div class="text-left">
                         <input type="password" name="cfmpwd" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                               title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                               id="cfmpwd" class="col-9 d-inline form-control form-control-sm">
                         </div>
                    </label>

                    <span id="message"></span><br><br>

                
                    <br>
                    <h3 class="formtitle">Change Phone Number</h3>
                    <p id="phone"><b>Phone number: <?php echo $one_member['phone']; ?></b></p>
                    <label class="lab" for="newphone" class="phonedis">New phone number:
                    <div class="text-left" class="phonedis">
                        <input type="tel" name="phone" class="col-9 d-inline form-control form-control-sm" placeholder="Phone Number"
                                      pattern="[89][0-9]{7}" title="phone number starts with 8 or 9 and must be 8 numbers"><br>
                    </div>
                    </label>

                    <br>
                    <label><input type="submit" name="submit" id="submit" value="Edit Profile"></label>

                </form>
                </section>
            </main>
        </body>
        <?php include "footer.inc.php"; ?>
        
<?php }?>
</html>

