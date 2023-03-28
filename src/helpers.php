<?php

use Mpietrucha\Minify\Minify;

if (! function_exists('minify')) {
    function minify(?string $contents = null, array $options = []): Minify|string {
        $handler = new Minify;

        if (! $contents) {
            return $handler
        }

        return $handler->content($contents)->options($options)->minify();
    }
}
