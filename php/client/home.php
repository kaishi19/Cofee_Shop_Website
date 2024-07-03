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

?>

    <section class="sec-a fxbx-b">
        <div class="fxbx-b main">
            <h1 class="tt-imgban">
                Hey<?php echo isset($_SESSION["username"]) ? ", ".ucwords(explode(" ", $_SESSION["username"])[0])."." : "."; ?>
                <br>Welcome to
                <br>Cozy Café.
            </h1>
            <p class="tx-imgban">
                Grab a great tasting cup of
                <br>coffee not a run-of-the mill brew.
            </p>
            <ul class="ul-a tx-imgban fxbx-c">
                <li>
                    <div class="fxbx-c"><i class='bx bx-book-add'></i><p>BOOKED</p></div>
                    <div class="fxbx-c"><span>1000+</span><p>Tables</p></div>
                </li>
                <li>
                    <div class="fxbx-c"><i class='bx bx-group'></i><p>SATED</p></div>
                    <div class="fxbx-c"><span>10000+</span><p>Patrons</p></div>
                </li>
                <li>
                    <div class="fxbx-c"><i class='bx bx-coffee' ></i><p>SERVED</p></div>
                    <div class="fxbx-c"><span>100000+</span><p>Cups</p></div>
                </li>
            </ul>
        </div>
    </section>

    <section class="sec-b fxbx-b">
        <div class="fxbx-b main">
            <h2 class="tt-imgban"> Open 24/7.</h2>
            <div class="fxbx-d">
                <div class="stt-imgban fxbx-d"><h3 class="stt-imgban"><span>Reservations</span> from <span>6AM</span> to <span>9PM</span>.</h3></div>
            </div>
            <p class="tx-imgban">Located at <span>402 Kapehan Street, Muzon, Naic, Cavite</span>.</p>
        </div>
    </section>

    <section class="sec-c fxbx-b">
        <div class="fxbx-b main">
            <div>
                <h2 class="tt-imgban">Cozy Reviews</h2>
            </div>
            <input type="radio" name="sld" id="in-a" checked>
            <input type="radio" name="sld" id="in-b">
            <input type="radio" name="sld" id="in-c">
            <div class="cdbx">
                <?php 
                    foreach ($reviewsResult as $review) {
                        echo '
                        <label class="fxbx-b cd" for="in-'.$review["id"].'" id="cd-'.$review["id"].'">
                            <div class="fxbx-b cd-cont">
                                <div class="rate fxbx-e tx-a">
                                    <div>
                                        <p>'.$review["rate"].'/5</p>
                                    </div>
                                    <div class="bxic fxbx-c">';
                                        echo str_repeat('<i class="bx bxs-star"></i>', $review["rate"]); echo'
                                    </div>
                                </div>
                                <img alt="'.$review["name"].'" class="img" src="'.$review["img"].'">
                                <h3 class="name stt-a">
                                '.$review["name"].'
                                </h3>
                                <p class="desc tx-a">"'.$review["desc"].'"</p>
                            </div>
                        </label>
                        ';
                    }
                ?>
            </div>
        </div>
    </section>
    
    <section class="sec-d fxbx-b ftr">
        <div class="fxbx-b main-main">
            <div class="fxbx-h">
                <div class="fxbx-f div-a">
                    <h3 class="tx-imgban">
                        Stay Cozy
                    </h3>
                    <ul>
                        <li>
                            <a href="mailto:thecozycafe@cozy.cafe" class="stx-imgban"><i class='bx bxs-envelope'></i><span>Cozy Mail</span></a>
                        <li>
                            <a href="https://www.facebook.com" class="stx-imgban"><i class='bx bxl-facebook-circle'></i><span>Cozy FB</span></a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com" class="stx-imgban"><i class='bx bxl-instagram-alt'></i><span>Cozy IG</span></a>
                        </li>
                        <li>
                            <a href="https://www.twitter.com" class="stx-imgban"><i class='bx bxl-twitter'></i><span>Cozy TWT</span></a>
                        </li>
                    </ul>
                </div>
                <div class="fxbx-f div-b">
                    <h3 class="tx-imgban">About Cozy Company</h3>
                    <p class="stx-imgban">
                        Indulge in the delightful world of handcrafted brews and delectable desserts. At The Cozy Cafe, we offer a wide selection of brews to satisfy your coffee cravings.
                    </p>
                </div>
                <div class="fxbx-f div-b">
                    <h3 class="tx-imgban">About Cozy Website</h3>
                    <p class="stx-imgban">
                        To enhance your visit, our website allows you to conveniently make reservations for your preferred date and time. Ensure a cozy spot awaits you as you step into our welcoming cafe.
                    </p>
                </div>
                <div class="fxbx-f div-b">
                    <h3 class="tx-imgban">About Cozy Staff</h3>
                    <p class="stx-imgban">
                        At The Cozy Cafe, our friendly and attentive staff is dedicated to ensuring your visit is memorable. From our knowledgeable baristas to our attentive servers, we strive to provide warm hospitality and exceptional service.
                    </p>
                </div>
            </div>
            <div class="fxbx-a">
                <p class="stx-imgban">© 2023 Cozy Company. All Rights Reserved.</p>
            </div>
        </div>
    </section>
    
    <div class="breathe breathe-b"></div>

</main>


<script src="../../js/main.js"></script>

</body>
</html>