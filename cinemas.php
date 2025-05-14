<!DOCTYPE html>
<html lang="en">
    <head>
        <title>WHERE TO WATCH</title>
        <?php
            include "header.inc.php";
        ?>
    </head>
    <body>
        <!-- Page Content -->
        <main>
        <div class="container-fluid bg-secondary" style='padding-top: 70px;'>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner" role="listbox" style="border: solid">
                       <?php
                       $counter =1;
                       global $cinemaName, $cinemaAddress, $mallWebsite, $cinemaImage1, $cinemaComment;
                       //query count of rows
                       $queryCount = $conn->query("SELECT * FROM cinema");
                       $count = $queryCount->num_rows;
                       //loop through row count
                       while($counter < $count+1){ 
                            $sql = "SELECT * FROM cinema WHERE cinemaID = $counter";
                            // Execute the query
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc(); 
								$cinemaId = $row["cinemaId"];								
                                $cinemaName = $row["cinemaName"];
                                $mallWebsite = $row["mallWebsite"];
                                $cinemaImage1 = $row["cinemaImage1"];
                                $cinemaComment = $row["cinemaComment"];
                            }
                           if($counter == 1){
                               echo "<div class='carousel-item active'>";
                           }
                           else{
                               echo "<div class=carousel-item>";
                           }
                            echo "
                                <a href=showtimes.php?cinemaId=" . $cinemaId . ">
                                    <img class='d-block img-fluid' src=$cinemaImage1 title='" . $cinemaName . "' alt='" . $cinemaName . "*' style='width: 100%'>
                                    <div class='carousel-caption'>
                                        <h5>$cinemaName</h5>
                                        <p class='d-none d-md-block'>$cinemaComment</p>
                                    </div>
                                </a>
                            </div>";
                            $counter = $counter + 1;
                       }
                            ?>

                        </div>              
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                       <?php
                       $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
                       $cinemaNum = 1;
                       $count = $queryCount->num_rows;
                       $hasRows = true;
                       while($cinemaNum < $count+1){
                            global $cinemaName, $cinemaAddress, $cinemaSypnosis, $mallWebsite, $cinemaImage1, $cinemaImage2;
                            $sql = "SELECT * FROM cinema WHERE cinemaId = $cinemaNum;";
                            // Execute the query
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $hasRows = true;
                                $row = $result->fetch_assoc();   //if no rows, pass
                                    $cinemaName = $row["cinemaName"];
                                    $cinemaAddress = $row["cinemaAddress"];
                                    $cinemaSynopsis = $row["cinemaSynopsis"];
                                    $mallWebsite = $row["mallWebsite"];
                                    $cinemaImage1 = $row["cinemaImage1"];
                                    $cinemaImage2 = $row["cinemaImage2"];
                            }
                            else{
                                $hasRows = false;
                            }
                            if ($hasRows == true){
                                if($cinemaNum == 1){
                                    echo "<div class=row>"; //opening tab, so wouldnt overlap.
                                }   
                                echo "
                                   <div class='col-lg-4 col-md-6 mb-4'>
                                        <div class='card h-100'>
                                            <a href=https://www.westmall.com.sg target=_blank>
                                                <img class=card-img-top src=$cinemaImage2 alt=$cinemaName>
                                            </a>
                                            <div class=card-body>
                                                <h4 class=card-title>
                                                    <a href=https://www.westmall.com.sg target=_blank>$cinemaName</a>
                                                </h4>
                                                <h5>$cinemaAddress</h5>
                                                <p class=card-text>$cinemaSynopsis</p>
                                            </div>
                                            <div class=card-footer>
                                                <small class=text-muted>&#9733; &#9733; &#9733; &#9733; &#9734;</small>
                                            </div>
                                        </div>
                                    </div>";
                                if($cinemaNum == $count){
                                    echo "</div>"; //closing tag
                                }
                                $cinemaNum = $cinemaNum + 1;  
                            }
                            else{
                                $cinemaNum = $cinemaNum +1;
                            }
                       }
                            ?>

                </div>
                <!-- /.col-lg-9 -->

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->
        
        <?php
            include "footer.inc.php";
        ?>
        </main>
    </body>

</html>
