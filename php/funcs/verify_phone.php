<?php

function verify_phone($phoneNumber) {
    // Check if phone number is empty
    if (empty($phoneNumber)) {
        return "pn=pnum_empty";
    }
    
    // Check if phone number has exactly 11 characters
    if (strlen($phoneNumber) !== 11) {
        return "pn=pnum_equal11";
    }
    
    // Check if phone number consists of numbers only
    if (!ctype_digit($phoneNumber)) {
        return "pn=pnum_mustdigit";
    }
    
    // Check if phone number starts with '09'
    if (substr($phoneNumber, 0, 2) !== '09') {
        return "pn=pnum_start09";
    }
    
    // If all checks pass, return true
    return "";
}

/*
// Function to verify phone number in the 'clt' table
function verifyPhoneNumberInClt($conn, $phoneNumber) {

    // Escape the phone number to prevent SQL injection
    $phoneNumber = $conn->real_escape_string($phoneNumber);

    // Query to check if the phone number exists in 'clt' table
    $sql = "SELECT p_num FROM clt WHERE p_num = '$phoneNumber'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Phone number exists in the 'clt' table
        return true;
    } else {
        // Phone number does not exist in the 'clt' table
        return false;
    }

    $conn->close();

}
// Connect to the database

// Query to fetch a random phone number from the 'clt' table. IDK the layout of the html and forms so i just did random
$sql = "SELECT p_num FROM clt ORDER BY RAND() LIMIT 1"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the phone number from the result
    $row = $result->fetch_assoc();
    $userPhoneNumber = $row['p_num'];

    // Verify the phone number
    if (verifyPhoneNumber($userPhoneNumber)) {
        if (verifyPhoneNumberInClt($conn, $userPhoneNumber)) {
            echo "Phone number is valid and exists in 'clt' table: $userPhoneNumber";
        } else {
            echo "Phone number is valid but does not exist in 'clt' table: $userPhoneNumber";
        }
    } else {
        echo "Phone number is not valid: $userPhoneNumber";
    }
} else {
    echo "No phone numbers found in 'clt' table.";
}
*/