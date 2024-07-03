<?php

function verify_pass($password, $in_signin = false) {
  // Check if the password is empty
  if (empty($password)) {
    return "ps=pass_empty";
  }
  if (!$in_signin) {
      // Check if the password is at least 8 characters long
    if (strlen($password) < 8) {
      return "ps=pass_less8";
    }
    // Check if the password is at most 12 characters long
    if (strlen($password) > 12) {
      return "ps=pass_more12";
    }
    // Check if the password contains at least one letter
    if (!preg_match('/[a-zA-Z]/', $password)) {
      return "ps=pass_noletter";
    }
    // Check if the password contains at least one special character
    if (!preg_match('/[|`|~|=|+|-|!|@|#|$|%|^|&|*|?|]/', $password)) {
      return "ps=pass_nospecial";
    }
    // Check if the password contains at least one number
    if (!preg_match('/[0-9]/', $password)) {
      return "ps=pass_nonumber";
    }
  }
  
  return "";
}

/*
// Function to verify password in the 'clt' table
function verifyPasswordInClt($password) {

    // Escape the password
    $password = $conn->real_escape_string($password);

    // Check if the password exists in the database
    $sql = "SELECT pass FROM clt WHERE pass = '$password'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
    // The password is valid
    return true;
    } 
    else {
    // The password is not valid
    return false;
    }
}
*/