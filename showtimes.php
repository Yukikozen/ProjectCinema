<!DOCTYPE html>

<html lang="en">
    <head>
        <title>WHEN TO WATCH</title>
        <?php
            include "header.inc.php";
        ?>
        <link href="css/showtimes_css.css" rel="stylesheet" type="text/css"/>
        <script>
            function ajaxShowtimeByCinema(cinemaId) {
                document.getElementById("showtimeContent").innerHTML = '<div class="d-flex justify-content-center" style="margin-top: 50px;"><div class="spinner-border" role="status" style="margin-right: 15px;"></div><strong>LOADING...</strong></div>';
                $.ajax( {
                    url:"process_showtimes.php", 
                    method:"POST", 
                    dataType: "HTML",
                    data: {cinemaId:cinemaId},                    
                    success: function (data) {
                        setTimeout(function(){document.getElementById("showtimeContent").innerHTML = data;$('[data-toggle="tooltip"]').tooltip();}, 250);
                    }
                });
            };
            
            function ajaxShowtimeByMovie(movieId) {
                document.getElementById("showtimeContent").innerHTML = '<div class="d-flex justify-content-center" style="margin-top: 50px;"><div class="spinner-border" role="status" style="margin-right: 15px;"></div><strong>LOADING...</strong></div>';
                $.ajax( {
                    url:"process_showtimes.php", 
                    method:"POST", 
                    dataType: "HTML",
                    data: {movieId:movieId},                    
                    success: function (data) {
                        setTimeout(function(){document.getElementById("showtimeContent").innerHTML = data;$('[data-toggle="tooltip"]').tooltip();}, 250);
                    }
                });
            };
                
            function changeSelect() {
                var selectBox = document.getElementById("cinemaSelect");
                var selectedValue = selectBox.options[selectBox.selectedIndex].value;
                ajaxShowtimeByCinema(selectedValue);
            };
            function changeButton(cinemaId) { 
                $("#cinemaSelect").val(cinemaId);
                ajaxShowtimeByCinema(cinemaId);
            };
            
            $(document).ready(function() {
                url = window.location.href;
                arr1 = url.split('movieId=')
                if (arr1.length == 1|| arr1[1] == "") {
                    arr2 = url.split('cinemaId=')
                    if (arr2.length == 1 || arr2[1] == "") {
                        labelTags = document.getElementsByTagName("LABEL");
                        labelTags[0].click();
                    }
                    else {
                        cinemaId = arr2[1];
                        idName = "cinema_".concat(cinemaId);                   
                        document.getElementById(idName).click();
                    }
                }
                else {
                    movieId = arr1[1];
                    ajaxShowtimeByMovie(movieId);
                }
            });
        </script>
    </head>
    <body>
        <main class="bg-secondary">
            <div class="container-fluid" style="margin-top: 70px; padding-top: 10px;">
                <div class="row container-fluid">
                    <?php
                    $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
                    $query = "SELECT * FROM cinema";

//                    $query = "SELECT * FROM p3_2.cinema";
                    $result = $conn->query($query);
                    $arrInfo = explode("movieId=", $_SERVER["REQUEST_URI"], 2);
                    if (count($arrInfo) == 2) {
                        if ($arrInfo[1] != "") {
                            $movieId = $arrInfo[1];
                            $movieQuery = "SELECT movieName, moviePoster, movieInfo FROM movie WHERE movieId = " . $movieId;
                            $movieResult = $conn->query($movieQuery)->fetch_assoc();
                            $movieName = $movieResult["movieName"];
                            $moviePoster = $movieResult["moviePoster"];
                            $movieInfo = $movieResult["movieInfo"];
                            echo "
                                <div class='col-md-2 d-md-block d-none'>
                                    <h4>Movie</h4>
                                    <div class='card bg-dark'>
                                        <img src='" . $moviePoster . "' alt='" . $movieName . "' class='rounded img-fluid'>
                                        <h5 class='card-title'>$movieName</h5>
                                        <p class='card-text'>$movieInfo</p>
                                    </div>
                                    <div class='btn-group-vertical'>
                                        <a href='showtimes.php' class='btn btn-light'>Show All Showtimes</a>
                                        <a href='films.php' class='btn btn-light'>Show All Movies</a>
                                    </div>
                                </div>
                                <div class='col-md-2 d-md-none'>
                                    <h4>Movie</h4>
                                    <div class='card bg-dark'>
                                        <h5 class='card-title'>$movieName</h5>
                                        <p class='card-text'>$movieInfo</p>
                                    </div>
                                    <div class='btn-group'>
                                        <a href='showtimes.php' class='btn btn-light' style='margin-bottom: 50px;'>Show All Showtimes</a>
                                        <a href='films.php' class='btn btn-light' style='margin-bottom: 50px;'>Show All Movies</a>
                                    </div>
                                </div>";
                            echo "<div style='display: none;'>";
                        }
                    }
                    ?>
                        <div class="col-md-2 d-md-block d-none">
                            <h3>Cinemas</h3>
                            <div class="btn-group-vertical btn-group-toggle" id="cinemaButtons" data-toggle="buttons">
                                <?php
                                $cinemaCount = 1;
                                while ($row = $result->fetch_assoc()) {
                                    $cinemaName = $row['cinemaName'];
                                    $cinemaId = $row['cinemaId'];
                                    if ($cinemaCount == 1) {
                                        echo "
                                            <label class='btn btn-light' id='cinema_" . $cinemaId . "' onclick='changeButton(" . $cinemaId . ")'>
                                                <input type='radio'>" . $cinemaName . "
                                            </label>";
                                    }
                                    else {
                                        echo "
                                            <label class='btn btn-light' id='cinema_" . $cinemaId . "' onclick='changeButton(" . $cinemaId . ")'>
                                                <input type='radio'>" . $cinemaName . "
                                            </label>";
                                    }
                                    $cinemaCount++;
                                }
                                ?>
                            </div>
                        </div>    
                        <div class="input-group col-md-2 d-md-none" style="margin-bottom: 10px;">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="cinemaSelect">Cinema</label>
                            </div>
                            <select class="custom-select" id="cinemaSelect" onchange="changeSelect()">
                                <?php
                                mysqli_data_seek($result, 0);
                                while ($row = $result->fetch_assoc()) {
                                    $cinemaName = $row['cinemaName'];
                                    $cinemaId = $row['cinemaId'];
                                    echo "<option value='" . $cinemaId . "'>" . $cinemaName . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    <?php
                    if (count($arrInfo) == 2 and $arrInfo[1] != "") {
                        echo "</div>";
                    }
                    ?>
                    
                    <div class="col-md-10">
                        <div class="row" style="margin-bottom: 0px;"><h3>Showtimes</h3></div>
                        <div class="row ml-auto" id="showtimeContent"></div>
                    </div>

                </div>

                <div class="row"></div>
            </div>

        </main>
    <?php
        include "footer.inc.php";
    ?>
    </body>
   
</html>
