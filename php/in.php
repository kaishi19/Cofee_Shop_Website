<!-- !!! -->

<?php

$page = "in";
$folder = "";

include_once "includes/dbh.php";
include "header.php";


$ps = $_GET["ps"] ?? "";
$em = $_GET["em"] ?? ""; 
$signin = $_GET["signin"] ?? ""; 

// admin pass = admin123!
?>

    <section class="sec-a fxbx-b">
            <div class="fxbx-b frbx">
                <form class="fxbx-b fr" action="includes/process.php" method="POST">
                    <h1 class="tt-imgban">Sign In</h1>
                    <div class="fxbx-g">
                        <label for="email" class="fr-lbl">Email address</label>
                        <input id="email" class="fr-in" type="text" name="email" placeholder="Enter email address..." >
                        <?php
                            if ($em == "email_empty") {
                                echo '<p class="err">Please fill in in this field.</p>';
                            } else if ($em == "email_invalid") {
                                echo '<p class="err">Please enter a valid email.</p>';
                            }
                        ?>
                    </div>
                    <div class="fxbx-g">
                        <label for="password" class="fr-lbl">Password</label>
                        <input id="password" class="fr-in"  type="password" name="pass" placeholder="Enter password..." >
                        <?php
                            if ($ps == "pass_empty") {
                                echo '<p class="err">Please fill in in this field.</p>';
                            } else if ($signin == "failed") {
                                echo '<p class="err">Incorrect login credentials.</p>';
                            } 
                        ?>
                    </div>
                    <button class="fr-btn" name="button" value="signin">Submit</button>
                    <!-- <button class="fr-btn" name="button" value="getrows">Get Rows</button> -->
                    <a class="fr-url" href="up.php">Don't have an account? Click here!</a>
                </form>
        </div>
    </section>

    <div class="breathe breathe-b"></div>
</main>

<script src="../js/main.js"></script>

</body>
</html>