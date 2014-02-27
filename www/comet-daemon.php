<?php
require_once '../classes/defines.php';
require_once SITE_PATH.'classes'.DS.'autoloader.php';

spl_autoload_register(['autoloader','load']);
spl_autoload_register(['autoloader', 'loadTrait']);
spl_autoload_register(['autoloader','loadInterface']);
spl_autoload_register(['autoloader','loadTemplate']);
spl_autoload_register(['autoloader','loadEditor']);
spl_autoload_register(['autoloader','loadExtension']);
exceptionHandler::initialise();

set_time_limit(0);

ob_implicit_flush(true);

$ip = getenv('REMOTE_ADDR');

if(!is_file(SITE_PATH.'resources'.DS.'comet.ini')) {
    file_put_contents(SITE_PATH.'resources'.DS.'comet.ini', '');
}

if(time() > (filemtime(SITE_PATH.'resources'.DS.'comet.ini') + 3600*48)) {
    file_put_contents(SITE_PATH.'resources'.DS.'comet.ini', '');
    error::log('clear comet.ini');
}

if(!file_exists(SITE_PATH.'resources'.DS.'comet-threads.ini')) {
    file_put_contents(SITE_PATH.'resources'.DS.'comet-threads.ini', '');
}

if(time() > (filemtime(SITE_PATH.'resources'.DS.'comet-threads.ini') + 3600*48)) {
    file_put_contents(SITE_PATH.'resources'.DS.'comet-threads.ini', '');
    error::log('clear comet-threads.ini');
}

$ini = factory::getIniServer(SITE_PATH.'resources'.DS.'comet-threads.ini');

$tid = md5(time().rand(0,50000));

$ini->write('tid', $ip, $tid);

$ini->updateFile();

$mode = preg_split('/\//', $_REQUEST['old_url'])[1];

$mode = $mode == 'admin' ? 'admin' : 'site';

//write user`s ip into the ini file
comet::clear_array($ip, $mode);

$sleep = 1;

$start = time() + 90;

while($start > time()) {
    $arr = comet::get_array($ip, $mode);

    if(count($arr) > 0) {
        break;
    }

    sleep($sleep);
}

$ini = factory::getIniServer(SITE_PATH.'resources'.DS.'comet-threads.ini');
$ctid = $ini->read('tid', $ip);
if($ctid != $tid) {
    die();
}

header('Content-type: text/plain');

if(count(comet::get_array($ip, $mode)) > 0) {
    echo json_encode(['task'=>'handle', 'msgs' => comet::get_array($ip, $mode)]);

    comet::clear_array($ip, $mode);
}
else {
    echo json_encode(['task'=>'reload']);
}