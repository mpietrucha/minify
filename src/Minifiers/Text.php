<?php

namespace Mpietrucha\Minify\Minifiers;

use voku\helper\HtmlMin;
use Mpietrucha\Minify\Contracts\MinifierInterface;

class Text implements MinifierInterface
{
    protected array $options;
    protected ?string $contents;

    protected const DEFAULT_OPTIONS = [
        'doRemoveOmittedQuotes' => false
    ];

    public function bootstrap(?string $contents, array $options): self
    {
        [$this->options, $this->contents] = [$options, $contents];

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
        return with(new HtmlMin, $this->applyOptions(...))->minify($this->contents);
    }

    public function gzip(): string
    {
        return gzencode($this->minify());
    }

    protected function applyOptions(HtmlMin $handler): HtmlMin
    {
        collect([...self::DEFAULT_OPTIONS, ...$this->options])->each(fn (mixed $mode, string $method) => $handler->$method($mode));

        return $handler;
    }
}
