<?php

function verify_fname($name) {
    // Check if the password is empty
    if (empty($name)) {
      return "fn=fname_empty";
    }
    // Check if the password contains at least one number
    if (!ctype_alpha($name)) {
      return "fn=fname_mustalpha";
    }
    return "";
    if (strlen($name) > 15) {
      return "fn=fname_exceeds15";
    }
  }

function verify_lname($name) {
    // Check if the password is empty
    if (empty($name)) {
      return "ln=lname_empty";
    }
    // Check if the password contains at least one number
    if (!ctype_alpha($name)) {
      return "ln=lname_mustalpha";
    }
    return "";
}