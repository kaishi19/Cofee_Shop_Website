<?php

function verify_email($email) {
    // Check if the password is empty
    if (empty($email)) {
      return "em=email_empty";
    }
    // Check if the password contains at least one number
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return "em=email_invalid";
    }
    return "";
}