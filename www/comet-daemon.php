<?php
require_once '../classes/defines.php';
require_once SITE_PATH.'classes'.DS.'comet.php';

set_time_limit(0);

ob_implicit_flush();

$sleep = 1;

$start = time() + 90;

while($start > time()) {
    $arr = comet::get_array();

    if(count($arr) > 0) {
        break;
    }

    sleep($sleep);
}

if(count(comet::get_array()) > 0) {
    echo json_encode(['task'=>'handle', 'msgs' => comet::get_array()]);

    comet::clear_array();
}
else {
    echo json_encode(['task'=>'reload']);
}