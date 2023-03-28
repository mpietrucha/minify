<?php

namespace Mpietrucha\Minify\Minifiers;

use voku\helper\HtmlMin;
use Mpietrucha\Minify\Contracts\MinifierInterface;

class Text implements MinifierInterface
{
    protected string $contents;

    public function bootstrap(?string $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    public function mimeTypes(): array
    {
        return ['*'];
    }

    public function extensions(): array
    {
        return ['*'];
    }

    public function minify(): string
    {
        return with(new HtmlMin, fn (HtmlMin $handler) => $handler->minify($this->contents));
    }

    public function gzip(): string
    {
        return gzencode($this->minify());
    }
}
