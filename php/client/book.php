<!-- client side:
NEW


cancel reservation 
1. #ABAIGAR client di makakapili ng table na maunti kaysa sa kasama niya (Ex. nilagay niya sa persons na pupunta sa table ay 4, magiging hindi available 3.for reservation yung tables with 3 seats and lower)
2. makikita ng client ang reservations niya (doon pwedeng pumasok ang cancel reservation, bawal sa iba)
3. if reserve table button is pressed, form will appear at the bottom of the page
4. #CRUZADA client, if not signed up, will be redirected to up.php


admin side: 
1. approve cancelation of reservation (because of money reasons)
2. cancel reservation
3. admin can see daily, weekly, monthly, yearly reservations
4. admin will see client in reserved table
5. admin will see next time of reservation


-->

<?php
    $page = "book";
    $folder = "client";

    include "../includes/dbh.php";
    include "../header.php";

    // checks if user is logged in
    if (!isset($_SESSION['logged_in'])) {
        echo '<script>
                alert("Please sign up before accessing the book page.");
                window.location.href = "../up.php?from=book";
                </script>';
        exit();
    }

    // client details
    $clientStatement = "SELECT * FROM `clt` WHERE `id` = ".$_SESSION["client_id"].";";
    $clientResult = mysqli_query($conn, $clientStatement);
    $client = $clientResult->fetch_assoc();

    $name = $client["f_name"]." ".$client["l_name"];
    $phone = $client["p_num"];
    $email = $client["email"];

    // client reservation details
    $clientReservationStatement = "SELECT `t`.`id` `table_id`, `t`.`seats` `table_seats`, `r`.`id` `reservation_id`, `r`.`time_in` `reservation_timein` , `r`.`time_out` `reservation_timeout`, `r`.`status` `reservation_status` FROM `rsv` `r` INNER JOIN `tbl` `t` ON `r`.`tbl_id` = `t`.`id` WHERE `r`.`clt_id` = ".$client["id"]." AND `r`.`status` IN ('pending', 'cancelling_pending', 'cancelling_reserved', 'reserved');";
    $clientReservationResult = mysqli_query($conn, $clientReservationStatement);

    // initializing of reservation values
    $date = "";
    $time = "";
    $ppl = "";
    $reservedTables = array();
    $logged_in = $_SESSION["logged_in"];
    $_POST["action"] = $_POST["action"] ?? "";
    $_SESSION["action"] = $_SESSION["action"] ?? "";

    // used for getting cafe tables' reserved and not reserved
    if ((isset($_POST["action"]) && ($_POST["action"] == "submit"))) {
        $_SESSION["date"] = $date = $_POST["date"];
        $_SESSION["time"] = $time = $_POST["time"];
        $_SESSION["ppl"] = $ppl = $_POST["ppl"];
        $_SESSION["action"] = $_POST["action"];
        $reservedStatement = "SELECT `tbl_id`, `status` FROM `rsv` WHERE `rsv`.`time_in` = '$date $time:00:00' AND `status` NOT IN ('completed', 'cancelled')  ORDER BY `rsv`.`tbl_id` ASC;";
        $tableStatement = "SELECT * FROM tbl;";

        $reservedResult = mysqli_query($conn, $reservedStatement);
        $tableResult = mysqli_query($conn, $tableStatement);
        
        foreach ($reservedResult as $reserved) {
            array_push($reservedTables, $reserved["tbl_id"]);
        }

    }  else if ((isset($_POST["action"]) && ($_POST["action"] == "next") && (!empty($_POST["date"])))) {
        $date = $_POST["date"];
        $date_is_chosen = true;
        $_SESSION["action"] = "";
    } else if ((isset($_SESSION["action"]) && ($_SESSION["action"] == "submit"))) {
        $date = $_SESSION["date"];
        $ppl = $_SESSION["ppl"];
        $time = $_SESSION["time"];

        $reservedStatement = "SELECT `tbl_id`, `status` FROM `rsv` WHERE `rsv`.`time_in` = '$date $time:00:00' AND `status` NOT IN ('completed', 'cancelled')  ORDER BY `rsv`.`tbl_id` ASC;";
        $tableStatement = "SELECT * FROM tbl;";

        $reservedResult = mysqli_query($conn, $reservedStatement);
        $tableResult = mysqli_query($conn, $tableStatement);
        
        foreach ($reservedResult as $reserved) {
            array_push($reservedTables, $reserved["tbl_id"]);
        }
    }
    
    // prepares values for showing reservation receipt and confirm their details
    if (isset($_GET["id"])) {
        $chosenTableStatement = "SELECT * FROM `tbl` WHERE `id` = ".$_GET["id"]."";
        $chosenTableResult = mysqli_query($conn, $chosenTableStatement);
        $chosenTable = $chosenTableResult->fetch_assoc();

        $table_seats = $chosenTable["seats"];
        $table_num = $chosenTable["id"];
        $table_price = $chosenTable["price"];
    }
?>
    <section class="rsvs sec-a">
        <div>
            <h1 class="rsvs-t tt-imgban">RESERVATIONS</h1>
        </div>
        <div class="rsvs-cdbx">
        <?php 
                if (mysqli_num_rows($clientReservationResult) == 0) {
                    echo '
                    <div class="fxbx-b cd-a">
                        <p class="rsvs-p">
                            You have no existing reservations!
                        </p>
                    </div>
                    ';
                } else {
                    foreach ($clientReservationResult as $clientReservation) {
                        if ($clientReservation["reservation_status"] == "cancelling_pending") {
                            $statusStatement = "Cancelling...";
                        } else if ($clientReservation["reservation_status"] == "cancelling_reserved") {
                            $statusStatement = "Cancelling Reservation...";
                        } else if ($clientReservation["reservation_status"] == "pending") {
                            $statusStatement = "Reserving...";
                        } else if ($clientReservation["reservation_status"] == "reserved") {
                            $statusStatement = "Reserved";
                        } else {
                            $statusStatement = "";
                        }
                        echo '
                        <div class="cd">
                            <div class="cd-hdr">
                                <h3 class="cd-tt">Table #'.$clientReservation["table_id"].'</h3>
                                <p class="seats">For '.$clientReservation["table_seats"].' Cozy Seat/s</p>
                            </div>
                            <div>
                                <p class="datetime">
                                    Date & Time
                                </p>
                                <p>
                                    '.$clientReservation["reservation_timein"].'
                                </p>
                            </div>
                            <div>
                                <p class="duration">Duration</p>
                                <p>1hr</p>
                            </div>
                            <div>
                                <p class="status">
                                    Reservation Status
                                </p>
                                <p>
                                    '.$statusStatement.'
                                </p>
                            </div>
                            ';
                            if ($clientReservation["reservation_status"] == "reserved") {
                                echo '
                                <a class="an-udl-ho" href="../includes/process.php?rsv_id='.$clientReservation["reservation_id"].'&req=cancelling_reserved">Cancel Reservation</a>
                                ';
                            } else if ($clientReservation["reservation_status"] == "pending") {
                                echo '
                                <a class="an-udl-ho" href="../includes/process.php?rsv_id='.$clientReservation["reservation_id"].'&req=cancelling_pending">Cancel Reservation</a>
                                ';
                            }  else if ($clientReservation["reservation_status"] == "cancelling_reserved") {
                                echo '
                                <a class="an-udl-ho" href="../includes/process.php?rsv_id='.$clientReservation["reservation_id"].'&req=uncancel_reserved">Cancel Request</a>
                                ';
                            } else if ($clientReservation["reservation_status"] == "cancelling_pending") {
                                echo '
                                <a class="an-udl-ho" href="../includes/process.php?rsv_id='.$clientReservation["reservation_id"].'&req=uncancel_pending">Cancel Request</a>';
                        }
                    echo '</div>';
                    } 
                }
                
            ?>
        </div>
    </section>
    <section class="sec-b book">
        <h1>RESERVE</h1>
        <div class="book-form">
            <form class="fr" action="book.php" id="bookform" method="POST">
                <input type="hidden" name="action" value="next">
                <h3>Date</h3>
                <input onchange="bookform.submit()" id="date" type="date" name="date" value="<?php echo $date ?>" min="<?php echo date("Y-m-d"); ?>" required>
            </form>
                <?php 
                    if (isset($date_is_chosen)) {
                        echo '
                        <form class="fr" action="book.php" method="POST" id="book-form">
                            <h3>Time</h3>
                            <input type="hidden" name="date" value="'.$date.'">
                            <select id="time" name="time" required>
                            <option disabled selected value="">Choose a time</option>
                        ';
                        if (($date !== date("Y-m-d")) && ($date !== "")) {
                            for ($i = 6; $i <= 20; $i++) {
                                $displayHour = ($i % 12 === 0) ? 12 : ($i % 12);
                                $timeDisplay = $displayHour . ":00 " . (($i >= 12 && $i !== 24) ? "PM" : "AM");
                                echo '<option value="'.$i.'">'.$timeDisplay.'</option>';
                            }
                        } else if ($date !== "") {
                            date_default_timezone_set("Asia/Manila");
                            $currentTime = intval(date("G"));
                            $startTime = max(6, $currentTime + 1); 
                            $endTime = 20; 

                            for ($i = $startTime; $i <= $endTime; $i++) {
                                $displayHour = ($i % 12 === 0) ? 12 : ($i % 12);
                                $timeDisplay = $displayHour . ":00 " . (($i >= 12 && $i !== 24) ? "PM" : "AM");
                                echo '<option value="'.$i.'">'.$timeDisplay.'</option>';
                            }
                        }
                        echo '
                            </select>
                            <h3>People</h3>
                            <input id="ppl" type="number" name="ppl" value="'.$ppl.'" min="1" max="12" placeholder="1-12" required>
                            <button type="submit" name="action" value="submit">Check Availability</button>
                        </form>
                        ';
                    }
                ?>
        </div>
    <?php 
        // shows table list for reserved and not reserved
        if ((isset($_POST["action"]) || isset($_SESSION["action"])) && (($_POST["action"] == "submit") || ($_SESSION["action"] == "submit"))) {
            echo '
            <div class="fxbx-a book-tbl-list">
                <input type="radio" name="sld" id="in-1" checked>
                <input type="radio" name="sld" id="in-2">
                <input type="radio" name="sld" id="in-3">
                <input type="radio" name="sld" id="in-4">
                <input type="radio" name="sld" id="in-5">
                <input type="radio" name="sld" id="in-6">
                <input type="radio" name="sld" id="in-7">
                <input type="radio" name="sld" id="in-8">
                <input type="radio" name="sld" id="in-9">
                <input type="radio" name="sld" id="in-10">
                <input type="radio" name="sld" id="in-11">
                <input type="radio" name="sld" id="in-12">
                <input type="radio" name="sld" id="in-13">
                <div class="cdbx">
            ';
                foreach ($tableResult as $table) {
                    echo '
                    <label class="fxbx-b cd" for="in-'.$table["id"].'" id="cd-'.$table["id"].'">
                        <div class="fxbx-b cd-cont">';
                    if (in_array($table["id"], $reservedTables)) {
                        if ($ppl > $table["seats"]) {
                            echo 
                            '
                            <div class="cd-cont-hdr">
                                <h3>
                                    Table #'.($table["id"]).'
                                </h3>
                                <p class="hdr-p">
                                    is reserved and is not available for '.$ppl.' people.
                                </p>
                            </div>
                            <div>
                                <img class="rsrv-layout" src="../../assoc/img/table_'.$table["id"].'.png">
                            </div>
                            <div class="btm-prt">
                                <div class="rcpt">
                                    <div>
                                        <p class="tt-p">'.$table["seats"].' Seat/s (1hr)</p>
                                    </div>
                                    <div>
                                        <p class="tt-p">P '.$table["price"].'</p>
                                    </div>
                                </div>
                            </div>
                            
                            ';
                        } else {
                            echo 
                            '
                            <div class="cd-cont-hdr">
                                <h3>
                                    Table #'.($table["id"]).'
                                </h3>
                                <p class="hdr-p">
                                    is reserved.
                                </p>
                            </div>
                            <div>
                                <img class="rsrv-layout" src="../../assoc/img/table_'.$table["id"].'.png">
                            </div>
                            <div class="btm-prt">
                                <div class="rcpt">
                                    <div>
                                        <p class="tt-p">'.$table["seats"].' Seat/s (1hr)</p>
                                    </div>
                                    <div>
                                        <p class="tt-p">P '.$table["price"].'</p>
                                    </div>
                                </div>
                            </div>

                            ';
                        }
                        
                    } else {
                        if ($ppl > $table["seats"]) {
                            echo 
                            ' 
                            <div class="cd-cont-hdr">
                                <h3>
                                    Table #'.($table["id"]).'
                                </h3>
                                <p class="hdr-p">
                                    is not reserved but is not available for '.$ppl.' people.
                                </p>
                            </div>
                            <div>
                                <img class="rsrv-layout" src="../../assoc/img/table_'.$table["id"].'.png">
                            </div>
                            <div class="btm-prt">
                                <div class="rcpt">
                                    <div>
                                        <p class="tt-p">'.$table["seats"].' Seat/s (1hr)</p>
                                    </div>
                                    <div>
                                        <p class="tt-p">P '.$table["price"].'</p>
                                    </div>
                                </div>
                            </div>

                            ';
                        } else {
                            echo 
                            '
                            <div class="cd-cont-hdr">
                                <h3>
                                    Table #'.($table["id"]).'
                                </h3>
                                <p class="hdr-p">
                                    is not reserved.
                                </p>
                            </div>
                            <div>
                                <img class="rsrv-layout" src="../../assoc/img/table_'.$table["id"].'.png">
                            </div>
                            <div class="btm-prt">
                                <div class="rcpt">
                                    <div>
                                        <p class="tt-p">'.$table["seats"].' Seat/s (1hr)</p>
                                    </div>
                                    <div>
                                        <p class="tt-p">P '.$table["price"].'</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="btm-prt-p">
                                        Note: Reservation is only cancellable and refundable until 10 minutes before the reserved time.
                                    </p>
                                </div>
                            </div>

                            <a class="an-udl-ho" href="book.php?id='.$table["id"].'">Reserve table</a> 
                            ';
                        }
                    }
                        echo
                        '
                        </div>
                    </label>';
                }
                echo 
                '
                </div>
            </div>';
        }
    echo '</section>';
    if (isset($_GET["id"])) {
        echo '
        <section class="rsvs sec-a">
            <div>
                <h1 class="rsvs-t tt-imgban">Confirm Reservation Details</h2>
            </div>
            <div class="rsvs-cdbx">
                    <div class="cd">
                        <div class="cd-hdr">
                            <h3>Table #'.$table_num.'</h3>
                            <p>
                                For '.$table_seats.' Cozy Seat/s
                            </p>
                            <p>
                                P '.$table_price.'
                            </p>
                        </div>
                        <div>
                            <p class="client">
                                Client Name
                            </p>
                            <p> 
                            '.ucwords($name).'
                            </p>
                            <p class="client">
                                Client Email Address
                            </p>
                            <p>
                                '.$email.'
                            </p>
                            <p class="client">
                                Client Contact Number
                            </p>
                            <p>
                                +63'.$phone.'
                            </p>
                        </div>
                        <div>
                            <p class="reserve">
                                Reservation Time In
                            </p>
                            <p>
                                '.$date.' '.$time.':00:00
                            </p>
                            <p class="reserve">
                                Reservation Time Out
                            </p>
                            <p>
                                '.$date." ".(intval($time) + 1).':00:00
                            </p>
                        </div>
                        <div>
                            <p class="gcash">
                            Company GCash Number
                            </p>
                            <p>
                                +639283087611<br>
                                John Ryan Salvador
                            </p>
                        </div>
                        <div class="cd-gc">
                            
                            <p class="gcash">
                                GCash Reference Number
                            </p>
                            <form class="fr" action="../includes/process.php" method="POST">
                                <input type="hidden" name="client_id" value="'.$client["id"].'">
                                <input id="name" type="hidden" name="name" value="'.ucwords($name).'" readonly>
                                <input id="phone" type="hidden" name="phone" value="'.$phone.'" readonly>
                                <input id="email" type="hidden" name="email" value="'.$email.'" readonly>
                                <input id="table_num" type="hidden" name="table_num" value="'.$table_num.'" readonly>
                                <input id="table_seats" type="hidden" name="table_seats" value="'.$table_seats.'" readonly>
                                <input id="rsv_datetime" type="hidden" name="start_datetime" value="'.$date." ".$time.":00:00".'" readonly>
                                <input id="rsv_end_datetime" type="hidden" name="end_datetime" value="'.$date." ".(intval($time) + 1).":00:00".'" readonly>
                                <input id="table_price" type="hidden" name="table_price" value="'.$table_price.'" readonly>
                                <input id="gcash_ref" type="text" name="gcash_ref" min="2" placeholder="#### ### ######" pattern="[0-9]{4} [0-9]{3} [0-9]{6}" required autofocus>
                                <button name="button" value="book">Reserve</button>
                            </form>
                        </div>
                </div>
            </div>
        </section>
        ';
    }
    ?>  
</body>
<script src="../../js/main.js"></script>
</html>