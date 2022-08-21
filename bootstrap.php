<?php

// Report all PHP errors
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
require_once 'src/autoload.php';

\RedBeanPHP\R::setup();
//\RedBeanPHP\R::fancyDebug(true);

define('REDBEAN_MODEL_PREFIX', '\\RedBeanBlog\\Model\\');
define('MYSQL_TIME_FMT', 'H:i:s');
