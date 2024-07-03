<?php
// NEW
    include_once "dbh.php";
    include_once "../funcs/verify_phone.php";
    include_once "../funcs/verify_pass.php";
    include_once "../funcs/verify_email.php";
    include_once "../funcs/verify_name.php";

    session_start();
    date_default_timezone_set("Asia/Manila");

    if ((isset($_POST["button"])) && ($_POST["button"] == "signup")) {
        $errors = array();
        $message = array();
        
        $l_name = strtolower(trim($_POST["l_name"]));
        $f_name = strtolower(trim($_POST["f_name"]));
        $pass = trim($_POST["pass"]);
        $cfrmpass = trim($_POST["cfrmpass"]);
        $p_num = trim($_POST["p_num"]);
        $email = strtolower(trim($_POST["email"]));

        

        $sql = "INSERT INTO clt (l_name, f_name, pass, p_num, email) VALUES (?, ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);

        // Necessary checks
        array_push($errors, verify_fname(implode(explode(' ', $f_name))), verify_lname(implode(explode(' ', $l_name))), verify_pass($pass), verify_email($email), verify_phone($p_num));

        $checkSql = "SELECT * FROM `clt` WHERE `email` = '$email'";
        $checkResult = mysqli_query($conn, $checkSql);
        
        if (mysqli_num_rows($checkResult)) {
            array_push($errors, "em=email_taken");
        }

        if (empty($cfrmpass)) {
            array_push($errors, "cps=pass_empty");
        } else {
            if ($pass !== $cfrmpass) {
                array_push($errors, "cps=pass_notmatch");
            }
        }

        foreach($errors as $error) {
            if (!empty($error)) {
                array_push($message, $error);
            }
        }

        if (!empty(implode($message))) {
            /* echo '
            <script type="text/javascript">
                document.ready(function() {
                    document.getElementById("submit").click();
                });
            </script>
            <form method="POST" action="../up.php?'.implode("&", $message).'">
                <input type="hidden" name="f_name" value="'.$f_name.'">
                <input type="hidden" name="l_name" value="'.$l_name.'">
                <input type="hidden" name="email" value="'.$email.'">
                <input type="hidden" name="p_num" value="'.$p_num.'">
                <input id="submit" name="submit" type="submit">
            </form>
           
            '; */
            header("Location: ../up.php?".implode("&", $message)."");
            $conn->close();
            exit();
        }

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../up.php?signup=failure");
            $conn->close();
            exit();
        }

        mysqli_stmt_bind_param($stmt, "sssis", $l_name, $f_name, password_hash($pass, PASSWORD_DEFAULT), $p_num, $email);
        mysqli_stmt_execute($stmt);

        $userSql = "SELECT * FROM `clt` WHERE `email` = '$email';";
        $userResult = mysqli_query($conn, $userSql);
        $user = $userResult->fetch_assoc();

        $_SESSION["logged_in"] = ($email == "admin@cozy.cafe") ? "admin" : "client";
        $_SESSION["username"] = $f_name;
        $_SESSION["client_id"] = $user["id"];
        
        if ((isset($_POST["from"]) && ($_POST["from"] == "book"))) {
            header("Location: ../".$_SESSION["logged_in"]."/book.php?signup=success");
        } else {
            header("Location: ../".$_SESSION["logged_in"]."/home.php?signup=success");
        }
        
        $conn->close();
        exit();

    } else if ((isset($_POST["button"])) && ($_POST["button"] == "signin")) {
        $errors = array();
        $message = array();
    
        $pass = trim($_POST["pass"]);
        $email = strtolower(trim($_POST["email"]));

        $sql = "SELECT * FROM `clt` WHERE `email` = ?";
        $stmt = mysqli_stmt_init($conn);

        array_push($errors, verify_email($email), verify_pass($pass, true));
       
        foreach($errors as $error) {
            if (!empty($error)) {
                array_push($message, $error);
            }
        }

        if (!empty(implode($message))) {
            header("Location: ../in.php?".implode("&", $message)."");
            $conn->close();
            exit();
        }

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../in.php?signin=failure");
            $conn->close();
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $user = $result->fetch_assoc();

        if (password_verify(trim($pass), trim($user["pass"]))) {
            $_SESSION["logged_in"] = ($email == "admin@cozy.cafe") ? "admin" : "client";
            $_SESSION["username"] = $user["f_name"];
            $_SESSION["client_id"] = $user["id"];
            header("Location: ../".$_SESSION["logged_in"]."/home.php?signin=success");
            $conn->close();
            exit();
        } else {
            header("Location: ../in.php?signin=failed");
            $conn->close();
            exit();
        }
    } else if ((isset($_POST["button"])) && ($_POST["button"] == "getrows")) {
        $sql = "SELECT * FROM clt";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../up.php?signup=failure");
            $conn->close();
            exit();
        } else {
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            foreach($result as $row) {
                echo $row["f_name"] . " " . $row["l_name"] . " " . $row["pass"] . " " . $row["p_num"] . " " . $row["email"] . "<br>";
                echo "Hello ", explode(" ",$row["f_name"])[0]."<br>";
            }
            echo '<a href="../up.php">back</a>';
            $conn->close();
            exit();
        }
    } else if ((isset($_POST["button"])) && ($_POST["button"] == "book")) {
        $gcash_ref = implode(explode(" ", trim(strval($_POST["gcash_ref"]))));

        $client_id = trim($_POST["client_id"]);
        $table_num = trim($_POST["table_num"]);
        $start_datetime = trim($_POST["start_datetime"]);
        $end_datetime = trim($_POST["end_datetime"]);
        $stat = "pending";

        $rsvCheckStatement = "SELECT * FROM `rsv` WHERE `tbl_id` = $table_num AND `time_in` = '$start_datetime' AND `status` NOT IN ('completed', 'cancelled');";
        $rsvCheckResult = mysqli_query($conn, $rsvCheckStatement);

        $rsvStatement = "INSERT INTO `rsv` ( `clt_id`, `tbl_id`, `time_in`, `time_out`, `status`,`updated_on`) VALUES ( ?, ?, ?, ?, ?, ?);";
        $rsvStmt = mysqli_stmt_init($conn);

        $rctStatement = "INSERT INTO `rct` (`rsv_id`, `gcash_ref`, `status`) VALUES (?, ?, ?);";
        $rctStmt = mysqli_stmt_init($conn);

        if (mysqli_num_rows($rsvCheckResult) > 0) {
            header("Location: ../".$_SESSION["logged_in"]."/book.php?booking=failed");
            $conn->close();
            exit();
        } 

        if (!mysqli_stmt_prepare($rsvStmt, $rsvStatement)) {
            header("Location: ../".$_SESSION["logged_in"]."/book.php?booking=failed");
            $conn->close();
            exit();
        }

        if (!mysqli_stmt_prepare($rctStmt, $rctStatement)) {
            header("Location: ../".$_SESSION["logged_in"]."/book.php?booking=failed");
            $conn->close();
            exit();
        }
        $currDate = ''.date("Y-m-d").'';

        mysqli_stmt_bind_param($rsvStmt, "iissss", $client_id, $table_num, $start_datetime, $end_datetime, $stat, $currDate);
        mysqli_stmt_execute($rsvStmt);

        $status = "verify";

        $rsv_id = mysqli_query($conn, "SELECT MAX(`id`) `id` FROM `rsv`;")->fetch_assoc()["id"];

        mysqli_stmt_bind_param($rctStmt, "iis", $rsv_id, $gcash_ref, $status);
        mysqli_stmt_execute($rctStmt);

        header("Location: ../".$_SESSION["logged_in"]."/book.php");
        $conn->close();
        exit();

    }

    if ((isset($_GET["rsv_id"])) && ($_GET["req"] == "cancelling_pending")) {
        $rsvSetSql = "UPDATE `rsv` SET `status` = 'cancelling_pending', `updated_on` = '".date("Y-m-d")."' WHERE `id` = ".$_GET["rsv_id"].";";
        mysqli_query($conn, $rsvSetSql);

        header("Location: ../".$_SESSION["logged_in"]."/book.php");
        $conn->close();
        exit();
    } else if ((isset($_GET["rsv_id"])) && ($_GET["req"] == "cancelling_reserved")) {
        $rsvSetSql = "UPDATE `rsv` SET `status` = 'cancelling_reserved', `updated_on` = '".date("Y-m-d")."' WHERE `id` = ".$_GET["rsv_id"].";";
        mysqli_query($conn, $rsvSetSql);

        header("Location: ../".$_SESSION["logged_in"]."/book.php");
        $conn->close();
        exit();
    }else if ((isset($_GET["rsv_id"])) && (($_GET["req"] == "cancel_reserved") || ($_GET["req"] == "cancel_pending"))) {
        $rsvSetSql = "UPDATE `rsv` SET `status` = 'cancelled', `updated_on` = '".date("Y-m-d")."' WHERE `id` = ".$_GET["rsv_id"].";";
        mysqli_query($conn, $rsvSetSql);
        
        $rctGetSql = "SELECT `id` FROM `rct` WHERE `rsv_id` = ".$_GET["rsv_id"].";";
        $rctGetResult = mysqli_query($conn, $rctGetSql);
        $rctSetSql = "UPDATE `rct` SET `status` = 'refunded' WHERE `id` = ".$rctGetResult->fetch_assoc()["id"]."";
        mysqli_query($conn, $rctSetSql);
    
        header("Location: ../".$_SESSION["logged_in"]."/book.php");
        $conn->close();
        exit();
    } else if ((isset($_GET["rsv_id"])) && ($_GET["req"] == "reserve")) {
        $rsvSetSql = "UPDATE `rsv` SET `status` = 'reserved', `updated_on` = '".date("Y-m-d")."' WHERE `id` = ".$_GET["rsv_id"].";";
        mysqli_query($conn, $rsvSetSql);

        $rctGetSql = "SELECT `id` FROM `rct` WHERE `rsv_id` = ".$_GET["rsv_id"].";";
        $rctGetResult = mysqli_query($conn, $rctGetSql);
        $rctSetSql = "UPDATE `rct` SET `status` = 'paid' WHERE `id` = ".$rctGetResult->fetch_assoc()["id"]."";
        mysqli_query($conn, $rctSetSql);

        header("Location: ../".$_SESSION["logged_in"]."/book.php");
        $conn->close();
        exit();
    } else if ((isset($_GET["rsv_id"])) && ($_GET["req"] == "cancel")) {
        $sql = "UPDATE `rsv` SET `status` = 'cancelled', `updated_on` = '".date("Y-m-d")."' WHERE `id` = ".$_GET["rsv_id"].";";
        mysqli_query($conn, $sql);

        $rctGetSql = "SELECT `id` FROM `rct` WHERE `rsv_id` = ".$_GET["rsv_id"].";";
        $rctGetResult = mysqli_query($conn, $rctGetSql);
        $rctSetSql = "UPDATE `rct` SET `status` = 'failed' WHERE `id` = ".$rctGetResult->fetch_assoc()["id"]."";
        mysqli_query($conn, $rctSetSql);

        header("Location: ../".$_SESSION["logged_in"]."/book.php");
        $conn->close();
        exit();
    } else if ((isset($_GET["rsv_id"])) && ($_GET["req"] == "uncancel_reserved")) {
        $rsvSetSql = "UPDATE `rsv` SET `status` = 'reserved', `updated_on` = '".date("Y-m-d")."' WHERE `id` = ".$_GET["rsv_id"].";";
        mysqli_query($conn, $rsvSetSql);

        header("Location: ../".$_SESSION["logged_in"]."/book.php");
        $conn->close();
        exit();
    }  else if ((isset($_GET["rsv_id"])) && ($_GET["req"] == "uncancel_pending")) {
        $sql = "UPDATE `rsv` SET `status` = 'pending', `updated_on` = '".date("Y-m-d")."' WHERE `id` = ".$_GET["rsv_id"].";";
        mysqli_query($conn, $sql);

        header("Location: ../".$_SESSION["logged_in"]."/book.php");
        $conn->close();
        exit();
    } 

header("Location: ../".$_SESSION["logged_in"]."/home.php?");
$conn->close();
exit();