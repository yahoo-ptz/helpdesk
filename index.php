<?php
    date_default_timezone_set('Europe/Moscow');
    require_once 'HelpDesk.php';

    $hd = new HelpDesk('config.php');
    $hd->checkMessages();
