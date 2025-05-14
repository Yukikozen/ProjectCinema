<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ABOUT US</title>
        <link href="css/aboutus_css.css" rel="stylesheet" type="text/css"/>
        <?php 
            include "header.inc.php";
        ?>        
    
   
        <main class="bg-secondary">
            
            <div class="container">
                <div class="row container-fluid bg-dark rounded">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#aboutus" data-toggle="tab" class="nav-link active">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a href="#cinemalocation" data-toggle="tab" class="nav-link">Cinema Locations</a>
                            </li>
                            <li class="nav-item">
                                <a href="#contactus" data-toggle="tab" class="nav-link">Contact Us</a>
                            </li>
                            <li class="nav-item">
                                <a href="#openinghours" data-toggle="tab" class="nav-link">Opening Hours</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="aboutus" data-toggle="tab">
                                <br>
                                <header><h3>About Us</h3>
                                <br>
                                <p id="description">Platinum Village @SIT , a wholly-owned by SIT Students, 
                                    is Singapore's cinema new concept for students and professor to enjoy premium movies theater in school and for events, located at Dover(SIT), NYP(SIT), SP(SIT), NP(SIT), TP(SIT) and RP(SIT) home to Singapore’s first all-laser cinema, and Bedok. 
                                    <br/><br/>
                                    PV is the first cinema concept that set up in school to personalise the movie-going experience through its Movie Club program. 
                                    Today, Platinum Village has a reputation of offering the widest choice of movies, state-of-the-art design, convenience and unparalleled comfort, with all cinemas newly set up since OCT 2019.</p>
                                </header>
                            </div>
                            <div class="tab-pane fade" id="cinemalocation" data-toggle="tab">
                                <br>
                                <h3>Cinema Locations</h3>
                                <br>
                                <div id="googleMap" class="map-responsive justify-content-center row">
                                    <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1JIPscuznTX3POzttNFnO53TSE6zgRmwT&ll=1.329751321207272%2C103.8471247&z=11" width="640" height="480" frameborder="0" style="border:solid" allowfullscreen></iframe>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contactus" data-toggle="tab">
                                <br>
                                <h3>Contact Us</h3>
                                <br>
                                <p>Address: Singapore Institute of Technology (NYP) <br>
                                    Telephone: +65 6752 4683<br>
                                    Email: nypsit@sit.singaporetech.edu.sg</p>
                            </div>
                            <div class="tab-pane fade" id="openinghours" data-toggle="tab">
                                <br>
                                <h3>Opening Hours</h3>
                                <br>
                                <p>Monday to Friday: 10am to 1am <br>
                                   Weekends and Public holidays: 9am to 2am</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        </body>
        <?php
            include "footer.inc.php";
        ?>

    

