<!DOCTYPE html>
<html lang="en">
    <head>
        <title>TRANSACTION</title>
        <link rel="stylesheet" href="css/tickets_css.css" />
        <?php 
        include "header.inc.php"; 

        ?>
    </head>    
</html>

<body>
<?php
    
$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
    $result4 = $conn->query("SELECT * FROM transaction");
    $trans_count = $result4->num_rows;
        
        
    // Fetch seats from DB
    //$result = $conn->query("SELECT * FROM p3_2.seat");
    

$firstname = $lastname = $email = $cvv = $contact = $cardnumber = $errorMsg = "";
$success = true;
if (empty($_POST["firstname"])) {
    $errorMsg .= "First Name is required.<br>";
    $success = false;
} 
else {
    $firstname = sanitize_input($_POST["firstname"]);
}
if (empty($_POST["lastname"])) {
    $errorMsg .= "Last Name is required.<br>";
    $success = false;
} 

else {
    $lastname = sanitize_input($_POST["lastname"]);
}
if (empty($_POST["contact"])) {
    $errorMsg .= "Contact number is required.<br>";
    $success = false;
} 
else {
    $contact = sanitize_input($_POST["contact"]);
}
if (empty($_POST["cardname"])) {
    $errorMsg .= "Name on credit card is required.<br>";
    $success = false;
} 
else {
    $cardname = sanitize_input($_POST["cardname"]);
}
if (empty($_POST["cardnumber"])) {
    $errorMsg .= "Credit card number is required.<br>";
    $success = false;
} 
else {
    $cardnumber = sanitize_input($_POST["cardnumber"]);
    $cardnumber = substr($cardnumber, -4);
}
if (empty($_POST["cvv"])) {
    $errorMsg .= "CVV is required.<br>";
    $success = false;
} 
else {
    $cvv = sanitize_input($_POST["contact"]);
}
if (empty($_POST["email"])) {
    $errorMsg .= "Email is required.<br>";
    $success = false;
} 
else {
    $email = sanitize_input($_POST["email"]);
// Additional check to make sure e-mail address is well-formed.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg .= "Invalid email format.";
        $success = false;
    }
}

if ($success) {
    saveMemberToDB();
    saveMemberToDB2();
    echo "<main>";
    echo "<h4>Purchase successful!</h4>";
    echo "<p>Email: " . $email;
    echo "<p>First Name: ". $firstname;
    echo "<p>Last Name: ". $lastname;
    echo "<p></p>";
    echo "<a href=index.php class='btn btn-primary' role=button>Return to main page</a>";
    echo "</main>";
} 

else {
    echo "<body>";
    echo "<h2>Oops</h2>";
    echo "<h3>The following input errors were detected:</h3>";
    echo "<p>" . $errorMsg . "</p>";
    echo "<a href=checkout.php class='btn btn-primary' role=button>Return to checkout</a>";
    echo "</body>";
    
}

//Helper function that checks input for malicious or unwanted content.
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function saveMemberToDB(){
    global $firstname, $contact, $cardnumber, $email, $errorMsg, $success;// Create connection
    //
    $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);// Check connection
    if ($conn->connect_error){
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    }
    else{
//        $sql = "INSERT INTO p3_2.transaction (creditCardNo, name, contactNo, email)";
//        $sql .= " VALUES('$cardnumber', '$firstname', '$contact', '$email')";// Execute the query
//        if (!$conn->query($sql)){
//            $errorMsg = "Database error: " . $conn->error;
//            $success = false;
//        }
        $sql = $conn->prepare("INSERT INTO transaction (creditCardNo, name, contactNo, email) VALUES(?,?,?,?)");
        $sql->bind_param('isis', $cardnumber, $firstname, $contact, $email);
        $result = $sql->execute();

    }
    $conn->close();
}
function saveMemberToDB2(){
    global $errorMsg, $success,$seatId,$movieTimingId, $trans_count, $trans_id;// Create connection
    $trans_id = $trans_count + 1;
    $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);// Check connection
    if ($conn->connect_error){
        $errorMsg = "Connection failed: " . $conn->connect_error;  $success = false;
    }
    else{      
        $movieTimingId = 9;
 
    $sql = "INSERT INTO bookedseat (movieTimingId, seatId,transactionId) VALUES ";
for($i=0;$i< $_SESSION["counter"];$i++){
        $movieTimingId = $_SESSION['movieTimingId'];
        $seatId = $_SESSION['array'][$i];
        $sql .= "($movieTimingId, '$seatId','$trans_id')";
            if ($i < ($_SESSION["counter"] - 1 )) {
                $sql .= ",";
            }
        }
        if (!$conn->query($sql)){
            $errorMsg = "Database error: " . $conn->error;
            $success = false; 
        }            
    }
    $conn->close();
}
include "footer.inc.php";
?>

</body>