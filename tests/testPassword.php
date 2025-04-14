<?php
$password = 'blu4';
$hash = '$2y$10$g4Z.bJ.atD.gQ4e8yxuUnuUyQRtHRKo4oyD.i3ZxszJTAS8.sYWbe';

if (password_verify($password, $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}