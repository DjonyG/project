<?php
$yiic='framework/yiic.php';

$config= include(__DIR__.'/config/console.php');

if(is_file(__DIR__.'/config/database.php')) {
    include(__DIR__.'/config/database.php');
}

require_once($yiic);