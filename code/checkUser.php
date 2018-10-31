<?php
require_once(dirname(__DIR__) . '/global_config.php');

function checkUser()
{
    if(!isset($_SESSION["id"]))
    {
        header("Location: login.php");
    }
}

?>