<!DOCTYPE html>
<html lang="en">
    <head>
        <title>BOOKING</title>
        <link href="css/tickets_css.css" rel="stylesheet" type="text/css"/>
        <script defer src="js/scripts.js"></script>
        <?php
            include "header.inc.php";
        ?>
    
    <?php
$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

$uri_arr = explode("movieTimingId=", $_SERVER["REQUEST_URI"], 2);
if (count($uri_arr) == 1){
    echo "<div class='bg-secondary' style='margin-top: -10px; padding: 50px;'>";
    echo "<div class='row' style='margin-left: 20px;'><p>No valid showtime selected.</p></div>";
    echo "
        <div>
            <a class='btn btn-light' onclick='history.back()'>Return Back</a>
            <a class='btn btn-light' href='showtimes.php'>Redirect to Showtimes</a>
        </div>";
    echo "</div>";
}
else {
    $movieTimingId = $uri_arr[1];

    $sql = 'SELECT cinemaName, movieTime, moviePoster, movieName, movieInfo FROM movietiming mvt 
            INNER JOIN movie mv ON mvt.movieId = mv.movieId
            INNER JOIN cinema cn ON mvt.cinemaId = cn.cinemaId
            WHERE movieTimingId = ' . $movieTimingId;
    $result = $conn->query($sql)->fetch_assoc();

    $cinemaName = $result["cinemaName"];
    $movieTime = $result["movieTime"];
    $movieTime = date('h:i A', strtotime($movieTime));
    $moviePoster = $result["moviePoster"];
    $movieName = $result["movieName"];
    $movieInfo = $result["movieInfo"];
?>
    <body>
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card bg-dark">
                            <?php 
                            echo "<img src=$moviePoster alt='" . $movieName . "' class='rounded img-fluid d-md-block d-none'>";
                            echo "<h4 class=card-title>$movieName</h4>";
                            echo "<p class=card-text>$movieInfo</p>";
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <br>
                        <div class="alert alert-danger" role="alert" id="error-no-seats">
                            Please select some seats before checking out!
                        </div>
                        <hr>
                        <?php
                             echo "<h3>You have selected <u>$movieName</u> for <u>$movieTime</u> at our <u>$cinemaName</u> outlet.</h3>";
                        ?>
                        <hr>
                        <div class="row text-center">
                            <div id="screen"></div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <?php
                                //session_start(); //session clashing
                                $_SESSION["movieTimingId"] = $movieTimingId;
                                ?>
                                <form method="post" id="checkout-form" action="checkout.php">
                                    <div class="seats">
                                        <?php
                                            $count = 0;
                                            $result = $conn->query("SELECT * FROM seat");
                                            while ($row = $result->fetch_assoc()) {
                                                $seat_id = $row["seatId"];
                                                $sql = $conn->prepare("SELECT * FROM bookedseat WHERE movieTimingId=? AND seatId=?");
                                                //$a = 9; 
                                                $sql ->bind_param('is', $movieTimingId, $seat_id);
                                                $sql ->execute();
                                                $sql->store_result();
                                                $sql->fetch();
                                                
                                                $count++;
                                                if ($sql->num_rows > 0){
                                                    echo "<input type=checkbox name=$seat_id id=$seat_id checked disabled/><label for=$seat_id class='seat'></label>";
                                                }
                                                else {
                                                    echo "<input type=checkbox name=$seat_id id=$seat_id /><label for=$seat_id class='seat'></label>";
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    if (count($uri_arr) == 2){
                                        echo '
//                                        <!-- Legend -->
                                        <div class="legend">
                                            <b class="legend-content">Legend:</b>
                                            <div class="legend-content"><div class="legend-seat legend-seat-taken"></div>Seat Taken</div>
                                            <div class="legend-content"><div class="legend-seat legend-seat-available"></div>Seat Available</div>
                                            <div class="legend-content"><div class="legend-seat legend-seat-selected"></div>Seat Selected</div>
                                        </div>
                                        <br>
                                        <!-- Buttons -->
                                        <div class="row">
                                            <div class="col-md-6 ">
                                                <a href="showtimes.php" class="btn btn-primary btn-block text-center">Back to Selection</a>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="#" class="btn btn-success btn-block text-center" id="btn-checkout">Checkout</a>
                                            </div>
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="checkoutModalLabel">Proceed to Checkout?</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        You are purchasing <span id="modal-ticket-count"></span> tickets for SGD$<span id="modal-ticket-price"></span>. Are you sure?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" id="modal-checkout" class="btn btn-success">Checkout</button>
                                                    </div>
                                                </div>
                                           </div>
                                        </div>';
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        </body>
        <?php include "footer.inc.php"; ?>
    
    <?php
        
        $conn->close()
    ?>

