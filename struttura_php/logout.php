<?php
require_once("../build/p_lib/session.php");
session_destroy();
echo '<script>window.location.href = "../public/login.php?lout";</script>';
?>