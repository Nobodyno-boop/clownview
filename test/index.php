<?php

require "../vendor/autoload.php";


$view = \Clownerie\ClownView\ClownView::newInstance([
    "path" => "./view/"
]);

// by token is load .view
$view->load("test");