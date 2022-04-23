<?php

require_once __DIR__ . './config/bootstrap.php';
use Danonek\Kernel\Providers\DatabaseHelper;
use Danonek\Kernel\Providers\CachedInfo;
use Danonek\Kernel\Tools\Utils;

$DbHelper = new DatabaseHelper();
Utils::deletePastesOnFileSystem($DbHelper->getAllExpiredPastes());
CachedInfo::cacheRecentPastes();
$DbHelper->cronJob();

?>