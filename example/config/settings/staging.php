<?php

$config = require 'production.php';

$config['phpSettings']['display_startup_errors'] = true;
$config['phpSettings']['display_errors']         = true;

$config['db']['dbname'] = 'epixaexampledb_staging';

return $config;