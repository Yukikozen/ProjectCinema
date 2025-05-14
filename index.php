<!DOCTYPE html>
<html lang="en">
    <head>
        <title>PLATINUM VILLAGE</title>
        <?php
            include "header.inc.php";
        ?>
    </head>
    <?php
    
    if(isset($_GET['login']) =='success') // login success, start the session
    {
        $message = "LOGIN SUCCESS!";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }

    if(isset($_GET['logout'])) // logout success, stop the session
    {
        $message = "LOG OUT SUCCESSFULLY!";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
    if(isset($_GET['update'])=='success') // RESET PASSWORD UPDATE SUCCESS
    {
        $message = "RESET PASSWORD SUCCESS! ";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
    ?>
    <?php
        $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $resultset = $conn->query("SELECT path,name FROM cinema_gallery"); // take name and path from sql cinema_gallery
        $image_count = 0; // initalise count as 0
        $rows = mysqli_fetch_assoc($resultset); //fetch name and path from table
    ?>
    <body>
        <main class="bg-secondary">
            <div class="container-fluid" style="padding-top: 90px;">
                <div class="row justify-content-center">
                    <div class = "col-lg-9">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <!--Indicator --> 
                            <ol class="carousel-indicators">
                                <?php
                                foreach ($resultset as $rows) {
                                    $actives = '';
                                    if (!$image_count) {
                                        $actives = 'active';
                                        $image_count++;
                                    }
                                    ?>
                                    <li data-target="#carouselExampleIndicators" data-slide-to= "<?= $image_count; ?>" class="<?= $actives; ?>"></li>
                                    <?php $image_count++;
                                } ?>

                            </ol>

                            <!--Slide show -->   
                            <div class="carousel-inner" style = "padding :10px">
                            <?php
                            $image_count = 0; // set count to 0 
                            foreach ($resultset as $rows) {
                                $actives = '';
                                if ($image_count == 0) {
                                    $actives = 'active';
                                }
                            ?>
                                    <div class="carousel-item <?= $actives; ?>">
                                        <img class="d-block-img-fluid" src="<?= $rows['path'] ?>" alt="<?= $rows['name'] ?>" style = "border: solid; width: 100%">
                                        <div class="carousel-caption">
                                            <h5><?= $rows['name'] ?></h5>
                                        </div>
                                    </div>
                            <?php $image_count++;
                            } ?>


                            </div>
                            <!-- toggle controls-->
                            <a class="left carousel-control-prev" href="#carousel-example-generic" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only"> Previous</span>
                            </a>
                            <a class="right carousel-control-next" href="#carousel-example-generic" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                            <ul class="thumbnails-carousel clearfix">

                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </main>
</body>
<?php
    include "footer.inc.php";
?>
    
</html>