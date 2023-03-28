<?php

namespace Mpietrucha\Minify\Contracts;

interface MinifierInterface
{
    public function bootstrap(?string $contents, array $options): self;

    public function mimeTypes(): array;

    public function extensions(): array;

    public function minify(): string;

    public function gzip(): string;
}
