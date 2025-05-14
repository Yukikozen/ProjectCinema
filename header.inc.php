
<?php 
include "config/config.php";

$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    if(isset($_SESSION['uname']))
    {
        $uname = $_SESSION['uname'];
        $sql = "SELECT * FROM memberinfo WHERE uname='$uname' ";
        $member_list = mysqli_query($conn, $sql);
        $one_member = mysqli_fetch_assoc($member_list);
    }
session_start();
?>

    
    <?php include "styles.inc.php";?>
</head>


<main>
    <nav class="navbar navbar-dark bg-dark navbar-expand-md fixed-top ">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="index.php">
                    <img src="images/header/PlatinumVillage_Logo.png" alt="PLATINUM_VILLAGE" style="max-height: 100px; max-width: 100px"
                         class="d-md-block d-none"/>
                    <img src="images/header/PlatinumVillage_Logo_Reduced.png" alt="PLATINUM_VILLAGE" style="max-height: 50px; max-width: 50px"
                         class="d-md-none d-block"/>
                </a>
            </div>
            <div class="ml-auto">
                <button class="navbar-toggler" type="button" title="button" data-toggle="collapse"
                        data-target="#collapse-navbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            
            <div class="collapse navbar-collapse" id="collapse-navbar">
                <div class="nav navbar-nav">
                    <a href="films.php" class="nav-link ml-auto">FILMS</a>
                    <a href="showtimes.php" class="nav-link ml-auto">SHOWTIMES</a> 
                    <a href="cinemas.php" class="nav-link ml-auto">CINEMAS</a> 
                    <a href="aboutus.php" class="nav-link ml-auto">ABOUT US</a> 
                    <div class="nav navbar-nav d-md-none" id="account_links">  
                        <a class="nav-link ml-auto" href="logout.php">
                            <span class="fa fa-user"></span>  LOGOUT
                        </a>
                    </div>
                </div>
            </div>
                
                <?php
                $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                if(isset($_SESSION['uname'])) {
                    $uname = $_SESSION['uname'];
                    $sql = "SELECT * FROM memberinfo WHERE uname='$uname' ";
                    $member_list = mysqli_query($conn, $sql);
                    $one_member = mysqli_fetch_assoc($member_list);
                }
                if(isset($_SESSION['uname']))
                {?>
            <div class="nav navbar-nav" id="account_links1">    
                <a class="nav-link ml-auto" href="account.php"><img id="profilepic" class="rounded-circle" alt="profile_pic" style = 'width: 35px; height:35px; ' src="
                    <?php

                    if($one_member['profilepic'] != "") {
                        echo $one_member['profilepic'];
                    }
                    else if(isset($_SESSION['uname']) && empty($one_member['profilepic'])){
                        echo "images/account/unknown-profile.png"; }?>"> 
                    Hi, <?=$uname;?>!
                </a>
            </div>
            <div class="nav navbar-nav d-md-block d-none" id="account_links2">  
                <a class="nav-link ml-auto" href="logout.php">
                    <span class="fa fa-user" name="logout"></span>  LOGOUT
                </a>
            </div>
                <?php } ?>
            <?php if(!isset($_SESSION['uname'])) { ?>
            <ul class="nav navbar-nav ml-auto" id="account_links3">
                <li class="nav-item" ><a href="login.php" class="nav-link ml-auto"><span class="fa fa-user"></span> ACCOUNT</a></li>
            </ul> 
            <?php }?>
        </div>
    </nav>
</main>

