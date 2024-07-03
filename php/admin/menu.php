<?php
$page = "menu";
$folder = "client";

include_once "../includes/dbh.php";
include "../header.php";

$img = "";
$rate = "";
$name = "";
$desc = "";

$reviewsStatement = "SELECT * FROM rvs;";

$reviewsResult = mysqli_query($conn, $reviewsStatement);

?>

    <section class="fxbx-b sec-a">
        <div class="main-main">
            <div class="imbx-a">
                <img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/b9de3855311699.597f8cc182753.gif" alt="">
                <img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/89f11f27516797.56366604759f3.gif" alt="">
                <img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/05d2dd25904477.5634c8ff8f632.gif" alt="">
                <img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/c383b555311699.597f8cc182ea5.gif" alt="">
                <img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/4f15d625904477.5634c8ff86bab.gif" alt="">
            </div>
            <div class="fxbx-f atl-a">
                <h3 class="tt-imgban">Cozy Spotlight</h3>
                <p class="tx-imgban">This cafe's best selling products include their signature espresso drinks, freshly baked pastries, and gourmet sandwiches. Customers rave about the rich, bold flavor of the coffee and the soft texture of the pancakes. The cafe's cozy atmosphere and friendly staff keep patrons coming back for more.</p>
            </div>
        </div>
    </section>
    
    <div class="breathe breathe-b"></div>

</main>


<script src="../../js/main.js"></script>

</body>
</html>