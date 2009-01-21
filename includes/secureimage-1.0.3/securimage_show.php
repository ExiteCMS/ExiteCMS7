<?php
require_once '../core_functions.php';
require_once 'securimage.php';

$img = new securimage();

$img->show(); // alternate use:  $img->show('/path/to/background.jpg');

// flush the session
session_clean_close();
?>
