<?php 
    date_default_timezone_set("Asia/Manila");
    session_start();
    $path = ($folder == "") ? "" : "../";

    $user = $_SESSION["logged_in"] ?? "client";
    
    // cleaning of unnecessary variables
    if ($page != "book") {
        unset($_SESSION["action"]);
        unset($_SESSION["time"]);
        unset($_SESSION["date"]); 
        unset($_SESSION["ppl"]);
    }

    // past and current reserved tables, based on reservations, will be open for reserving again (completed)
    $checkStatement = "SELECT `id` FROM `rsv` WHERE `status` = 'reserved' AND `time_out` <= '".date("Y-m-d H:00:00")."'";
    $checkResult = mysqli_query($conn, $checkStatement);  
    foreach ($checkResult as $check) {
        $reserveToCompleted = "UPDATE `rsv` SET `status` = 'completed', `updated_on` = '".date("Y-m-d")."' WHERE `id` = ".$check["id"]."";
        mysqli_query($conn, $reserveToCompleted);
    }   
?>

<!DOCTYPE html>

<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="https://64.media.tumblr.com/fbfc27b3af7ec9a7e4a3d18dc2f465c1/c042e3cf45c4c9f1-f2/s1280x1920/daef74f4a22b6abbefb4d9474cf0fcfc54d240b1.pnj">
    <title>Cozy Café</title>

    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css">

    <?php 
    if (isset($outside)) {
        echo '
        <link rel="stylesheet" charset="UTF-8" type="text/css" href="css/style.css">
        <link rel="stylesheet" charset="UTF-8" type="text/css" href="css/header.css">
        <link rel="stylesheet" charset="UTF-8" type="text/css" href="css/home.css">
    ';
    } else {
        echo '  
        <link rel="stylesheet" charset="UTF-8" type="text/css" href="../'; echo $path; echo 'css/style.css">
        <link rel="stylesheet" charset="UTF-8" type="text/css" href="../'; echo $path; echo 'css/header.css">
        <link rel="stylesheet" charset="UTF-8" type="text/css" href="../'; echo $path; echo 'css/'; echo $page; echo '.css">
        ';
    }
    ?>
</head>

<body>
    <header>
        <nav class="nav-t fxbx-a" id="nav">
            <a href="<?php echo (isset($outside)) ? "php/client/home.php" : (($folder == "client") ? "" : "client/");?>" class="cozy navit-hl fxbx-a navit an-udl-ho">
                <i class='bx bx-coffee-togo'></i><span>COZY CAFÉ</span>
            </a>
            <div class="nav-b">
                <ul class="fxbx-a">
                    <li>
                        <?php 
                        if (isset($outside)) {
                            echo '
                            <a href="php/client/home.php" class="navit an-udl-ho '; echo ($page == "home" ? "navit-hl navit-ac" : ""); echo ' fxbx-a">
                                <i class="bx bx-home-circle"></i><span>HOME</span>
                            </a>
                            ';
                        } else {
                            echo '
                            <a href="'; echo ($folder == "client") ? "" : "client/"; echo 'home.php" class="navit an-udl-ho '; echo ($page == "home" ? "navit-hl navit-ac" : ""); echo ' fxbx-a">
                                <i class="bx bx-home-circle"></i><span>HOME</span>
                            </a>
                            ';
                        }
                        ?>
                    </li>
                    <li>
                        <?php
                            if (isset($outside)) {
                                echo '
                                <a href="php/client/menu.php" class="navit an-udl-ho '; echo ($page == "menu" ? "navit-hl navit-ac" : ""); echo ' fxbx-a">
                                    <i class="bx bx-food-menu"></i><span>MENU</span>
                                </a>
                                ';
                            } else {
                                echo '
                                <a href="'; echo ($folder == "client") ? "" : "client/"; echo 'menu.php" class="navit an-udl-ho '; echo ($page == "menu" ? "navit-hl navit-ac" : ""); echo ' fxbx-a">
                                    <i class="bx bx-food-menu"></i><span>MENU</span>
                                </a>
                                ';
                            }
                        ?>
                    </li>
                    <li>
                        <?php
                            if (isset($outside)) {
                                echo '
                                <a href="php/client/book.php" class="navit an-udl-ho '; echo ($page == "book" ? "navit-hl navit-ac" : ""); echo ' fxbx-a">
                                    <i class="bx bx-bowl-hot"></i><span>BOOK</span>
                                </a>
                                ';
                            } else {
                                echo '
                                <a href="'; echo ($folder == "client") ? "" : "client/"; echo 'book.php" class="navit an-udl-ho '; echo ($page == "book" ? "navit-hl navit-ac" : ""); echo ' fxbx-a">
                                    <i class="bx bx-bowl-hot"></i><span>BOOK</span>
                                </a>
                                ';
                            }
                        ?>
                    </li>
                    <li>
                        <?php
                            if (isset($outside)) {
                                echo '
                                <a href="'; echo !isset($_SESSION["logged_in"]) ? "php/in.php" : "php/out.php"; echo '"class="navit an-udl-ho '; echo ($page == "in" ? "navit-hl navit-ac" : ""); echo' fxbx-a">
                                    <i class="bx bx-'; echo !isset($_SESSION["logged_in"]) ? "log-in" : "log-out"; echo'"></i><span>'; echo !isset($_SESSION["logged_in"]) ? "IN" : "OUT";echo'</span>
                                </a>
                                ';
                            } else {
                                echo '
                                <a href="'; echo !isset($_SESSION["logged_in"]) ? $path."in.php" : $path."out.php"; echo '"class="navit an-udl-ho '; echo ($page == "in" ? "navit-hl navit-ac" : ""); echo' fxbx-a">
                                    <i class="bx bx-'; echo !isset($_SESSION["logged_in"]) ? "log-in" : "log-out"; echo'"></i><span>'; echo !isset($_SESSION["logged_in"]) ? "IN" : "OUT";echo'</span>
                                </a>
                                ';
                            }
                        ?>
                    </li>
                </ul>
            </div>
            <ul class="fxbx-a">
                <!-- <li>
                    <i class='navit an-udl-ho bx bx-info-circle'></i>
                </li>
                <li>
                    <i class='navit an-udl-ho bx bx-help-circle'></i>
                </li> -->
                <li>
                    <i onclick="toggleDarkMode()" class='navit an-udl-ho bx bx-moon' id="mode"></i>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="breathe breathe-a"></div>