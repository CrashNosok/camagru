<?php
function check_password($passwd) {
    $little_letter = 0;
    $big_letter = 0;
    $digit = 0;
    if (strlen($passwd) < 8) {
        return false;
    }
    for ($i = 0; $i < strlen($passwd); $i++) {
        if (is_numeric($passwd[$i])) {
            $digit = 1;
        }
        else if (ctype_upper($passwd[$i])) {
            $big_letter = 1;
        }
        else if (!ctype_upper($passwd[$i])) {
            $little_letter = 1;
        }
    }
    if ($little_letter && $big_letter && $digit) {
        return true;
    } else {
        return false;
    }
}
?>