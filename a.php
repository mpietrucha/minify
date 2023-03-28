<?php

require_once 'vendor/autoload.php';

use Mpietrucha\Minify\Minify;

dd(
    Minify::path('logo.svg')->minify()
);
