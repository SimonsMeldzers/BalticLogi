<?php
session_start();

session_destroy();

header("Location: /BalticLogi/public/login.php");
exit;
