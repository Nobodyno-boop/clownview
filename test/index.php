<?php
require "../vendor/autoload.php";
\Spatie\Ignition\Ignition::make()
    ->register();

$view = \Clownerie\ClownView\ClownView::newInstance([
    "path" => "./view/"
]);




$view->load("test");