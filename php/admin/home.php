<?php
$page = "home";
$folder = "client";

include_once "../includes/dbh.php";
include "../header.php";

$img = "";
$rate = "";
$name = "";
$desc = "";

$reviewsStatement = "SELECT * FROM rvs;";
$reviewsResult = mysqli_query($conn, $reviewsStatement);

// Daily/Weekly/Monthly/Yearly variable initializations
$stats = array("daily", "weekly", "monthly", "yearly");
$total = 0;
$completed = 0;
$processing = 0;
$reserved = 0;
$cancelled = 0;
$cancelling = 0;
$statsStatements = array();
array_push($statsStatements, "SELECT COUNT(`status`) `total_status`, `status` FROM `rsv` WHERE `updated_on` = CURRENT_DATE GROUP BY `status`;");
array_push($statsStatements, "SELECT COUNT(`status`) `total_status`, `status` FROM `rsv` WHERE WEEK(`updated_on`) = WEEK(CURRENT_DATE) GROUP BY `status`;");
array_push($statsStatements, "SELECT COUNT(`status`) `total_status`, `status` FROM `rsv` WHERE MONTH(`updated_on`) = MONTH(CURRENT_DATE) GROUP BY `status`;");
array_push($statsStatements, "SELECT COUNT(`status`) `total_status`, `status` FROM `rsv` WHERE YEAR(`updated_on`) = YEAR(CURRENT_DATE) GROUP BY `status`;");

?>
    <section class="dbrd fxbx-b sec-e">
        <div class="main-main">
            <div class="main-hdr">
                <h1>
                    Cozy Café STATISTICS
                </h1>
            </div>
            <div class="main-cdbx">
                <?php
                    $counter = 0;
                    foreach ($statsStatements as $statement) {
                        $total = 0;
                        $completed = 0;
                        $processing = 0;
                        $reserved = 0;
                        $cancelled = 0;
                        $cancelling = 0;
                        
                        $result = mysqli_query($conn, $statement);
                        foreach ($result as $record) {
                            $completed += ($record["status"] == "completed") ? $record["total_status"] : 0;
                            $reserved += ($record["status"] == "reserved") ? $record["total_status"] : 0;
                            $processing += ($record["status"] == "pending") ? $record["total_status"] : 0;
                            $cancelled += ($record["status"] == "cancelled") ? $record["total_status"] : 0;
                            $cancelling += ($record["status"] == "cancelling_reserved" || $record["status"] == "cancelling_pending") ? $record["total_status"] : 0;
                            $total = $completed + $reserved + $processing + $cancelled + $cancelling;
                        }
                        echo '
                            <div class="main-cd">
                                <h3>
                                    '.ucwords($stats[$counter]).'
                                </h3> 
                                <p>
                                    Total '.$total.' 
                                </p> 
                                <p class="p-completed">Completed '.$completed.'</p>
                                <p class="p-reserved">Reserved '.$reserved.'</p>
                                <p class="p-cancelled">Cancelled '.$cancelled.'</p>
                                <p style="display: '. ((($stats[$counter] == "yearly") || ($stats[$counter] == "monthly")) ? "none" : "default").';">Processing '.$processing.'</p>
                                <p style="display: '. ((($stats[$counter] == "yearly") || ($stats[$counter] == "monthly")) ? "none" : "default").';">Cancelling '.$cancelling.'</p>
                                <svg>
                                    <circle id="container" cx="70" cy="'.(($stats[$counter] == "yearly") || ($stats[$counter] == "monthly") ? "60" : "53").'" r="48"></circle>
                                    
                                    <circle id="completed" cx="70" cy="'.(($stats[$counter] == "yearly") || ($stats[$counter] == "monthly") ? "60" : "53").'" r="40" style="stroke-dashoffset: '.(265 - intval((($completed / $total) * 260))).';"></circle>
                                    <circle id="reserved" cx="70" cy="'.(($stats[$counter] == "yearly") || ($stats[$counter] == "monthly") ? "60" : "53").'" r="30" style="stroke-dashoffset: '.(295 - intval((($reserved / $total) * 260))).';"></circle>
                                    <circle id="cancelled" cx="70" cy="'.(($stats[$counter] == "yearly") || ($stats[$counter] == "monthly") ? "60" : "53").'" r="20" style="stroke-dashoffset: '.(295 - intval((($cancelled / $total) * 260))).';"></circle>
                                </svg>
                            </div>
                            
                            ';
                        $counter += 1;
                    }
                ?>
            </div>
        </div>
    </section>

    <div class="breathe breathe-b"></div>

</main>


<script src="../../js/main.js"></script>

</body>
</html>