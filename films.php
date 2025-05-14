<!DOCTYPE html>
<html lang="en">
    <head>
        <title>WHAT TO WATCH</title>
        <script defer src="js/films.js" type="text/javascript"></script>
        <link href="css/films_css.css" rel="stylesheet" type="text/css"/>
        <?php
            include "header.inc.php";
        ?>
    </head>
    <body>
        <main class="bg-secondary" style="padding-top: 70px;">
            <div class="container-fluid">
                <?php
                    $moviePoster = $movieName = $movieInfo = $movieTrailer = $errorMsg = "";
                    $movieId = $movieCount = 0;
                    $success = true;

                    function displayFilms() {
                        global $movieId, $moviePoster, $movieName, $movieInfo, $movieTrailer, $movieCount;
                        
                        if ($movieCount == 0) {
                            echo '<div class="row">';
                        }
                        else if ($movieCount % 4 == 0) {
                            echo '</div>';
                            echo '<div class="row">';
                        }
                        echo '
                            <div class="col-6 col-md-3 d-flex align-items-stretch">
                                <div class="card bg-dark">
                                    <img src="' . $moviePoster . '" alt="' . $movieName . '" class="rounded img-fluid">
                                    <h5 class="card-title">' . $movieName . '</h5>
                                    <p class="card-text">' . $movieInfo . '</p>
                                    <button type="button" class="btn btn-primary mt-auto" data-toggle="modal" data-target="#movie' . $movieCount . '">Trailer</button>
                                    <div class="modal fade" id="movie' . $movieCount . '" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="kill_iframe(' . 'movie' . $movieCount . 'iframe' . ')">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="embed-responsive embed-responsive-16by9">
                                                        <iframe class="embed-responsive-item" class="movie1iframe" src="' . $movieTrailer . '" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="showtimes.php?movieId=' . $movieId . '" class="btn btn-warning" class="linkbutton">Showtimes</a>
                                </div>
                            </div>';
                   }
                   
                    function getFilmsFromDB() {
                        global $movieId, $moviePoster, $movieName, $movieInfo, $movieTrailer, $movieCount, $errorMsg, $success;

                        // Create connection
                        $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

                        // Check connection
                        if ($conn->connect_error) {
                            $errorMsg .= "Connection failed: " . $conn->connect_error;
                            $success = false;
                        } 
                        else {
                            $sql = "SELECT * FROM movie;";

                            // Execute the query
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) { 
                                    $movieId = $row["movieId"];
                                    $moviePoster = $row["moviePoster"];
                                    $movieName = $row["movieName"];
                                    $movieInfo = $row["movieInfo"];
                                    $movieTrailer = $row["movieTrailer"];
                                    displayFilms(); 
                                    $movieCount++;
                               }
                            } 
                            else {
                                $errorMsg .= "No movies found...";
                                $success = false;
                            }
                            $result->free_result();
                        }
                        $conn->close();
                   } 
                   
                   getFilmsFromDB();
                ?>                     
            </div>
        </main>
        <?php
            include "footer.inc.php";
        ?>
    </body>
</html>