<!DOCTYPE HTML>
<html>
    <head>
        <?php include "config/config.php"; ?>
        <title>Upload Validation</title>
        <?php
            function doSaveData($uname, $profilepic)
            {
                $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                $sql = $conn->prepare("UPDATE memberinfo SET profilepic=? where uname=?");
                $sql ->bind_param('ss', $profilepic, $uname);
                $result = $sql->execute();
                
                if($result)
                {
                    echo "<br>" . "Data Saved.";
                    echo "<br>" . $_SESSION['uname'];
                    header("Location:account.php?uploadsuccess=upload");
                }
                else
                {
                    header("Location:account.php?uploadfail=upload");
                }
            }
        ?>
    </head>
    <body>
        <?php
            session_start();
            echo "You have selected " . $_FILES["fileToUpload"]["name"] . "<br>";
            echo "The file size is " . $_FILES["fileToUpload"]["size"] . "<br>";
            echo "The file type is " . $_FILES["fileToUpload"]["type"] . "<br>";

            date_default_timezone_set("Asia/Singapore");
            $timestamp = date("Ymd_His"); //Construct the timestamp

            //Add timestamp to the filename
            $target = "images/account/" . $timestamp . $_FILES["fileToUpload"]["name"];
            $allowedType = array("image/jpeg","image/jpg", "image/png");

            if(in_array ($_FILES["fileToUpload"]["type"] , $allowedType))
            {
                echo "File Type is acceptable" . "<br>"; //proceed to upload
            }
            else
            {
                echo "Invalid file type" . "<br>";
                header("Location:account.php?uploadfail=upload");
                exit();
            }

            if($_FILES["fileToUpload"]["size"] < 1000000) //larger than 1MB
            {
                echo "File Size is acceptable" . "<br>";
            }
            else
            {
                header("Location:account.php?uploadfail2=large");
                echo "File is too large" . "<br>";
                
                exit();
            }
            $result = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],$target);
            if($result)
            {
                echo "Upload Success";
                $uname = $_SESSION['uname'];
                doSaveData($uname,$target);
            }
            else
            {
                echo "Upload FAILED" . "<br>";
            }
        ?>
    </body>
</html>