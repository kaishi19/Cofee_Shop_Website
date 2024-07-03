<?php
    $page = "up";
    $folder = "";
    include_once "includes/dbh.php";
    include "header.php";
    $fn = $_GET["fn"] ?? "";
    $ln = $_GET["ln"] ?? ""; 
    $ps = $_GET["ps"] ?? "";
    $pn = $_GET["pn"] ?? "";    
    $em = $_GET["em"] ?? "";   
    $cps = $_GET["cps"] ?? "";

    $f_name = $_POST["f_name"] ?? "";
    $l_name = $_POST["l_name"] ?? "";
    $p_num = $_POST["p_num"] ?? "";
    $email =$_POST["email"] ?? "";
?>
    <section class="sec-a fxbx-b">
        <div class="fxbx-b frbx">
            <form class="fxbx-b fr" action="includes/process.php" method="POST">
                <h1 class="tt-imgban">Sign Up</h1>
                <div class="fxbx-g">
                    <label for="f_name" class="fr-lbl">First Name</label>
                    <input id="f_name" class="fr-in" type="text" name="f_name" value="<?php echo "$f_name"; ?>" placeholder="Enter first name..." >
                    <?php
                        if ($fn == "fname_empty") {
                        echo '<p class="err">Please fill in in this field</p>';
                        } else if ($fn == "fname_mustalpha") {
                        echo '<p class="err">Must only contain letters</p>';
                        } else if ($fn == "fname_exceeds15") {
                        echo '<p class="err">First name must not exceed 15 letters</p>';
                        }
                    ?>
                </div>
                <div class=fxbx-g>
                    <label for="l_name" class="fr-lbl">Last Name</label>
                    <input id="l_name" class="fr-in" type="text" name="l_name" value="<?php echo "$l_name"; ?>" placeholder="Enter last name..." >
                    <?php
                        if ($ln == "lname_empty") {
                        echo '<p class="err">Please fill in in this field</p>';
                        } else if ($ln == "lname_mustalpha") {
                        echo '<p class="err">Must only contain letters</p>';
                        }
                    ?>
                </div>
                <div class=fxbx-g>
                    <label for="email" class="fr-lbl">Email Address</label>
                    <input id="email" class="fr-in" type="text" name="email" value="<?php echo "$email"; ?>" placeholder="Enter email address..." >

                    <?php
                        if ($em == "email_empty") {
                        echo '<p class="err">Please fill in in this field</p>';
                        } else if ($em == "email_invalid") {
                        echo '<p class="err">Please enter a valid email</p>';
                        }  else if ($em == "email_taken") {
                        echo '<p class="err">Email has been taken, please enter another email</p>';
                        }
                    ?>
                </div>
                <div class=fxbx-g>
                    <label for="p_num" class="fr-lbl">Phone Number</label>
                    <input id="p_num" class="fr-in" type="text" name="p_num" value="<?php echo "$p_num"; ?>" placeholder="Enter phone number..." >
                
                    <?php
                        if ($pn == "pnum_empty") {
                        echo '<p class="err">Please fill in in this field</p>';
                        } else if ($pn == "pnum_equal11") {
                        echo '<p class="err">Phone number must be 11 numbers</p>';
                        } else if ($pn == "pnum_mustdigit") {
                        echo '<p class="err">Phone number must only contain numbers</p>';
                        } else if ($pn == "pnum_start09") {
                        echo '<p class="err">Phone number must start with "09"</p>';
                        }
                    ?>
                </div>
                <div class=fxbx-g>
                    <label for="password" class="fr-lbl">Password</label>
                    <input id="password" class="fr-in" type="password" name="pass" placeholder="Enter password..." >
                
                    <?php
                        if ($ps == "pass_empty") {
                        echo '<p class="err">Please fill in in this field</p>';
                        } else if ($ps == "pass_less8") {
                        echo '<p class="err">Password must be at least 8 characters</p>';
                        } else if ($ps == "pass_more12") {
                        echo '<p class="err">Password must not exceed 12 characters</p>';
                        } else if ($ps == "pass_noletter") {
                        echo '<p class="err">Password must have at least one letter</p>';
                        }  else if ($ps == "pass_nospecial") {
                        echo '<p class="err">Password must have at least one special character</p>';
                        }  else if ($ps == "pass_nonumber") {
                        echo '<p class="err">Password must have at least one number</p>';
                        }
                    ?>
                </div>
                <div class=fxbx-g>
                    <label for="cfrmpass" class="fr-lbl">Confirm Password</label>
                    <input id="cfrmpass" class="fr-in" type="password" name="cfrmpass" placeholder="Confirm password..." >

                    <?php
                        if ($cps == "pass_notmatch") {
                        echo '<p class="err">Passwords do not match</p>';
                        } else if ($cps == "pass_empty") {
                        echo '<p class="err">Please confirm password</p>';
                        }
                    ?>
                </div>
                <input type="hidden" name="from" value="<?php echo $_GET["from"] ?? ""; ?>">
                <button class="fr-btn" name="button" value="signup">Submit</button>
                <a class="fr-url" href="in.php">Already have an account? Click here!</a>
                <!-- <button class="fr-btn" name="button" value="getrows">Get Rows</button> -->
            </form>
        </div>
    </section>
<script src="../js/main.js"></script>
</body>
</html>

