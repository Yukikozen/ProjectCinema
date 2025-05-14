<!DOCTYPE html>
<html lang="en">
    <head>
        <title>CHECKOUT</title>
        <link href="css/tickets_css.css" rel="stylesheet" type="text/css"/>
        <script src="https://code.jquery.com/jquery-3.4.1.js" crossorigin="anonymous"></script>   
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <?php include "header.inc.php" ?>
    </head>
    <body>
        <main class="container">    
            <br>
            <div class="container">
                <h1>Order Summary</h1>
                <div class="row">
                    <?php
                    echo "<div class=col-6>You have selected the following seat(s): </div>";
                    $counter = 0;
                    $array = array();
                    if (!empty($_POST)) {
                        echo "<div class='col-6 text-right'>";
                        foreach ($_POST as $key => $value) {
                            echo "#$key ";
                            $array[$counter] = $key;
                            $counter = $counter + 1;
                        }
                        echo "</div>";
                    } else {
                        header("Location: booking.php");
                    }
                    
                    if (!empty($_SESSION)) {

                        $_SESSION["array"] = $array;
                        $_SESSION["counter"] = $counter;
                        //$test = $_SESSION["uname"];

                        // Get email and phone from database
                        if(isset($_SESSION['uname'])){
                              
                        $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
                        $sql = "SELECT email, phone FROM memberinfo WHERE uname = \"" . $_SESSION["uname"] . "\"";
                        $result = $conn->query($sql)->fetch_assoc();
                        
                        $user_email = $result["email"];
                        $user_phone = $result["phone"];
                        }
                       
                    } else {
                        header("Location: booking.php");
                    }

                    ?>

                    </div>
                <hr>
                <div class="row">
                    <?php
                    echo "<div class=col-6>Total:</div>";
                    $price = $counter * 7.00;
                    echo "<div class='col-6 text-right'>$ $price</div>";
                    ?>  
                </div>
            </div>
        </main>

        <main class="container">   

            <div class="container py-5">
                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <form id="userinfo" action="transaction.php" method="post">
                            <h1>User Information</h1>
                            <div class="form-group row">
                                <div class="col-sm-6">

                                    <label for="inputFirstname">First name</label>
                                    <input type="text" class="form-control" name="firstname" id="FirstName" placeholder="First name" required="required" />
                                </div>
                                <div class="col-sm-6">
                                    <label for="inputLastname">Last name</label>
                                    <input type="text" class="form-control" name="lastname" id="lastName" placeholder="Last name" required="required"/>                 
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for=inputContactNumber>Contact Number</label>
                                    <input type="text" class="form-control" name="contact" id="inputContactNumber" placeholder="Contact Number" value="<?php echo (isset($user_phone)) ? $user_phone : ''; ?>"/>
                                </div>
                                <div class=col-sm-6>
                                    <label for=inputEmail>Email</label>
                                    <input type="email" class="form-control" name="email" id="inputEmail" placeholder="Email" value="<?php echo (isset($user_email)) ? $user_email : ''; ?>"/>
                                </div>
                            </div>
                            <h1>Payment Information</h1>
                            <div class='form-group row'>
                                <div class="col-sm-6">
                                    <label for="inputName">Name on card</label>
                                    <input type="text" class="form-control" name="cardname" id="cardName" placeholder="cardname" />
                                </div>
                                <div class="col-sm-6">
                                    <label for="inputCardNumber">Card Number</label>
                                    <input type="text" class="form-control" name="cardnumber" id="cardNumber" placeholder="cardnumber" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="inputExpiryMonth">Expiry Month</label>
                                    <select class="browser-default custom-select" required>
                                        <option selected>Select Month</option>
                                        <option value=1>01</option>
                                        <option value=2>02</option>
                                        <option value=3>03</option>
                                        <option value=4>04</option>
                                        <option value=5>05</option>
                                        <option value=6>06</option>
                                        <option value=7>07</option>
                                        <option value=8>08</option>
                                        <option value=9>09</option>
                                        <option value=10>10</option>
                                        <option value=11>11</option>
                                        <option value=12>12</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputExpiryYear">Expiry Year</label>
                                    <select class="browser-default custom-select">
                                        <option selected>Select Year</option>
                                        <option value=1>2019</option>
                                        <option value=2>2020</option>
                                        <option value=3>2021</option>
                                        <option value=4>2022</option>
                                        <option value=5>2023</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputCVV">CVV</label>
                                    <input type="password" class="form-control" name="cvv" id="cvv" placeholder="CVV"/>
                                </div>
                            </div>
                            <?php
                            foreach ($_POST as $key => $value) {
                            echo "<input type=checkbox name=checkboxes[$key] checked/>";
                            }
                        ?>
                            <button type="submit" class='btn btn-primary px-4 float-right'>Checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <script src="js/scripts.js"></script>
        
        <?php include "footer.inc.php"; ?>
    </body>


</html>