<html lang="en">
<head><title>process showtime</title></head>
<?php
include "config/config.php";

$html_data = '';
$errorMsg = "";
$success = true;
$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);


function testMovieId($movieId) {
    global $conn;
    $sqlMovie = "SELECT * FROM movie WHERE movieId = $movieId";

//    $sqlMovie = "SELECT * FROM p3_2.movie WHERE movieId = $movieId";
    $resultMovie = $conn->query($sqlMovie);
    if ($resultMovie->num_rows > 0) {
        return true;
    }
    return false;
}


function getShowtimeStatus($movieTimingId, $movieTime) {
    global $html_data, $conn;
    $sqlSeats = "SELECT * FROM seat;";

//    $sqlSeats = "SELECT * FROM p3_2.seat;";
    $sqlBookedSeats = "SELECT * FROM bookedseat WHERE movieTimingId = " . $movieTimingId;
//        $sqlBookedSeats = "SELECT * FROM p3_2.bookedseat WHERE movieTimingId = " . $movieTimingId;

    $resultSeats = $conn->query($sqlSeats);
    $noOfSeats = $resultSeats->num_rows;
    $resultSeats->free_result();
    $resultBookedSeats = $conn->query($sqlBookedSeats);
    $noOfBookedSeats = $resultBookedSeats->num_rows;
    $resultBookedSeats->free_result();
    $percentage = $noOfBookedSeats / $noOfSeats * 100;
    if ($percentage == 100) {
        $html_data .= '<button type="button" class="btn btn-disabled disabled" data-toggle="tooltip" data-placement="top" title="SOLD OUT">' . $movieTime . '</button>';
    }
    elseif ($percentage >= 50) {
        $html_data .= '<button type="button" class="btn btn-sellingfast" data-toggle="tooltip" data-placement="top" title="SELLING FAST" onclick="window.location=' . "'booking.php?movieTimingId=" . $movieTimingId . "'" . '">' . $movieTime . '</button>';
    }
    else {
        $html_data .= '<button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="AVAILABLE" onclick="window.location=' . "'booking.php?movieTimingId=" . $movieTimingId . "'" . '">' . $movieTime . '</button>';
    }
}

function filterByCinema($cinemaId) {
    global $html_data, $conn, $currTime, $dateArr, $sqlDateArr;
    $sqlMovie = "SELECT * FROM movie;";

//    $sqlMovie = "SELECT * FROM p3_2.movie;";
    $resultMovie = $conn->query($sqlMovie);
    if ($resultMovie->num_rows > 0) {
        $movieCount = 1;
        while ($rowMovie = $resultMovie->fetch_assoc()) {
            $movieId = $rowMovie["movieId"];
            $movieName = $rowMovie["movieName"];
            $movieInfo = $rowMovie["movieInfo"];

            $html_data .= '
                    <div class="row container-fluid bg-dark rounded">
                        <div class="col-md-4">
                            <div class="card bg-dark">
                                <h5 class="card-title">' . $movieName . '</h5>
                                <p class="card-text">' . $movieInfo . '</p>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <ul class="nav nav-tabs">';

            for ($dateCount = 0; $dateCount < 5; $dateCount++) {
                if ($dateCount == 0) {
                    $html_data .= '
                                <li class="nav-item">
                                    <a href="#movie' . $movieCount . 'day' . $dateCount . '" data-toggle="tab" class="nav-link active">' . $dateArr[$dateCount] . '</a>
                                </li>';
                } else {
                    $html_data .= '
                                <li class="nav-item">
                                    <a href="#movie' . $movieCount . 'day' . $dateCount . '" data-toggle="tab" class="nav-link">' . $dateArr[$dateCount] . '</a>
                                </li>';
                }
            }

            $html_data .= '</ul>';
            $html_data .= '<div class="tab-content">';

            for ($dateCount = 0; $dateCount < 5; $dateCount++) {
                $sqlTiming = "SELECT * FROM movietiming WHERE ";

//                $sqlTiming = "SELECT * FROM p3_2.movietiming WHERE ";
                $sqlTiming .= "cinemaId = " . $cinemaId . " AND movieId = " . $movieId;
                $sqlTiming .= " AND movieDate = cast('" . $sqlDateArr[$dateCount] . "' AS datetime)";

                if ($dateCount == 0) {
                    $sqlTiming .= " AND CAST(movieTime AS TIME) > '" . $currTime . "'";
                }
                $sqlTiming .= " ORDER BY movieTime";
                $result = $conn->query($sqlTiming);

                if ($dateCount == 0) {
                    $html_data .= '<div class="tab-pane fade active show" id="movie' . $movieCount . "day" . $dateCount . '" data-toggle="tab">';
                } else {
                    $html_data .= '<div class="tab-pane fade" id="movie' . $movieCount . "day" . $dateCount . '" data-toggle="tab">';
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $movieTimingId = $row["movieTimingId"];
                        $movieTime = $row["movieTime"];
                        $movieTime = date('h:i A', strtotime($movieTime));
                        $seatStatus = getShowtimeStatus($movieTimingId, $movieTime);
                    }
                }
                $html_data .= '</div>';
                $result->free_result();
            }

            $html_data .= '      
                            </div>
                        </div>
                    </div>';
            $movieCount++;
        }
    }
    $resultMovie->free_result();
}

function filterByMovie($movieId) {
    global $html_data, $conn, $currTime, $dateArr, $sqlDateArr;
    
    if (testMovieId($movieId) == false) {
        $html_data .= "<h6>Error: No such movie found</h6>";
        return;
    }
    $sqlCinema = "SELECT * FROM cinema;";

//    $sqlCinema = "SELECT * FROM p3_2.cinema;";
    $resultCinema = $conn->query($sqlCinema);
    if ($resultCinema->num_rows > 0) {
        $cinemaCount = 1;
        while ($rowCinema = $resultCinema->fetch_assoc()) {
            $cinemaId = $rowCinema["cinemaId"];
            $cinemaName = $rowCinema["cinemaName"];

            $html_data .= '
                    <div class="row container-fluid bg-dark rounded">
                        <div class="col-md-4">
                            <div class="card bg-dark">
                                <h5 class="card-title">' . $cinemaName . '</h5>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <ul class="nav nav-tabs">';

            for ($dateCount = 0; $dateCount < 5; $dateCount++) {
                if ($dateCount == 0) {
                    $html_data .= '
                                <li class="nav-item">
                                    <a href="#movie' . $cinemaCount . 'day' . $dateCount . '" data-toggle="tab" class="nav-link active">' . $dateArr[$dateCount] . '</a>
                                </li>';
                } else {
                    $html_data .= '
                                <li class="nav-item">
                                    <a href="#movie' . $cinemaCount . 'day' . $dateCount . '" data-toggle="tab" class="nav-link">' . $dateArr[$dateCount] . '</a>
                                </li>';
                }
            }

            $html_data .= '</ul>';
            $html_data .= '<div class="tab-content">';

            for ($dateCount = 0; $dateCount < 5; $dateCount++) {
                $sqlTiming = "SELECT * FROM movietiming WHERE ";

//                $sqlTiming = "SELECT * FROM p3_2.movietiming WHERE ";
                $sqlTiming .= "cinemaId = " . $cinemaId . " AND movieId = " . $movieId;
                $sqlTiming .= " AND movieDate = cast('" . $sqlDateArr[$dateCount] . "' AS datetime)";

                if ($dateCount == 0) {
                    $sqlTiming .= " AND CAST(movieTime AS TIME) > '" . $currTime . "'";
                }
                $sqlTiming .= " ORDER BY movieTime";
                $result = $conn->query($sqlTiming);

                if ($dateCount == 0) {
                    $html_data .= '<div class="tab-pane fade active show" id="movie' . $cinemaCount . "day" . $dateCount . '" data-toggle="tab">';
                } else {
                    $html_data .= '<div class="tab-pane fade" id="movie' . $cinemaCount . "day" . $dateCount . '" data-toggle="tab">';
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $movieTimingId = $row["movieTimingId"];
                        $movieTime = $row["movieTime"];
                        $movieTime = date('h:i A', strtotime($movieTime));
                        $seatStatus = getShowtimeStatus($movieTimingId, $movieTime);
                    }
                }
                $html_data .= '</div>';
                $result->free_result();
            }

            $html_data .= '      
                            </div>
                        </div>
                    </div>';
            $cinemaCount++;
        }
    }
    $resultCinema->free_result();
}

function getShowtimesFromDB() {
    global $errorMsg, $success, $html_data, $conn, $currTime, $dateArr, $sqlDateArr;
    $dateCount = 0;

    if ($conn->connect_error) {
        $errorMsg .= "Connection failed: " . $conn->connect_error;
        $success = false;
    } 
    else {
        date_default_timezone_set("Asia/Singapore");
        $date = date("d-m-Y");
        $sqlDate = date("Y-m-d");
        $currTime = date("H:i:s");
        $dateArr = [$date];
        $sqlDateArr = [$sqlDate];

        while ($dateCount < 5) {
            $date = date("d-m-Y", strtotime("1 day", strtotime($date)));
            $sqlDate = date("Y-m-d", strtotime("1 day", strtotime($sqlDate)));
            array_push($dateArr, $date);
            array_push($sqlDateArr, $sqlDate);
            $dateCount++;
        }
        
        if (isset($_POST["cinemaId"])) {
            $cinemaId = filter_input(INPUT_POST, "cinemaId");
            filterByCinema($cinemaId);
        }
        elseif (isset($_POST["movieId"])) {
            $movieId = filter_input(INPUT_POST, "movieId");
            filterByMovie($movieId);
        }
        else {
            echo "<html>
                    <body>
                        <h1>ERROR 403: FORBIDDEN</h1>
                        <p>Sorry, your access has been refused due to security reasons.</p>
                    </body>
                  </html>";
            return;
        }                   
    }
    $conn->close();
    echo $html_data;
}


getShowtimesFromDB();
?>
</html>

