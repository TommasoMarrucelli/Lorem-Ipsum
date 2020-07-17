<?php
session_start();

print_r(json_encode($log_msg)); ('Welcome '. $_SESSION["username"]);

session_destroy();
?>