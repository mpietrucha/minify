<?php

namespace Mpietrucha\Minify\Minifier;

use Mpietrucha\Minify\Contracts\MinifierInterface;
use MatthiasMullie\Minify\CSS as Handler;
use MatthiasMullie\Minify\Minify;

class Css implements MinifierInterface
{
    protected Minify $handler;

    public function bootstrap(?string $contents, array $options): self
    {
        if ($contents) {
            $this->handler = new Handler($contents);
        }

        return $this;
    }

    public function mimeTypes(): array
    {
        return ['text/css'];
    }

    public function extensions(): array
    {
        return ['css'];
    }

    public function minify(): string
    {
        return $this->handler->minify();
    }

    public function gzip(): string
    {
        return $this->handler->gzip();
    }
}
