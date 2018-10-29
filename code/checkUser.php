<?php
require_once(dirname(__DIR__) . '/global_config.php');

function checkUser()
{
    if(!isset($_SESSION["name"]))
    {
        header("Location: login.php");
    }
}
?>