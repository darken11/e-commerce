<?php
session_start(); //start the session
session_unset(); //unset the data
session_destroy();
header('Location:index.php');
exit();
