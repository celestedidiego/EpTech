<?php
    $password = 'admin'; // Testo in chiaro
    $hash = password_hash($password, PASSWORD_BCRYPT);

    echo $hash; // Testo cifrato
    echo "\n";

    if (password_verify($password, $hash)) {
        echo 'Password is valid!';
    } else {
        echo 'Invalid password.';
    }

?>