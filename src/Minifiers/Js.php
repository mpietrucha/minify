<?php

namespace Mpietrucha\Minify\Minifiers;

use Mpietrucha\Minify\Contracts\MinifierInterface;
use MatthiasMullie\Minify\Js as Handler;
use MatthiasMullie\Minify\Minify;

class Js implements MinifierInterface
{
    protected Minify $handler;

    public function bootstrap(?string $contents): self
    {
        if ($contents) {
            $this->handler = new Handler($contents);
        }

        return $this;
    }

    public function mimeTypes(): array
    {
        return ['text/javascript'];
    }

    public function extensions(): array
    {
        return ['js'];
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
