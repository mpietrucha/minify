<?php

namespace Mpietrucha\Minify;

use Mpietrucha\Support\Concerns\HasFactory;
use Mpietrucha\Support\Concerns\HasInputFile;
use Mpietrucha\Support\Types;
use Mpietrucha\Minify\Contracts\MinifierInterface;
use Mpietrucha\Minify\Minifiers\Text;
use Mpietrucha\Minify\Minifiers;
use Mpietrucha\Macros\Bootstrapper;
use Illuminate\Support\Stringable;

class Minify
{
    use HasFactory;
    use HasInputFile;

    protected ?MinifierInterface $minifier;

    protected const MINIFIERS = [
        Minifiers\Js::class,
        Minifiers\Css::class
    ];

    public function __construct(protected ?string $contents = null, protected array $options = [])
    {
        Bootstrapper::handle();

        $this->minifier();
    }

    public function __call(string $method, array $arguments): ?string
    {
        if (! $this->contents) {
            return null;
        }

        return $this->minifier->$method(...$arguments);
    }

    public function contents(string $contents): self
    {
        $this->contents = $contents;

        $this->bootstrapped();

        return $this;
    }

    public function options(array $options = []): self
    {
        $this->options = $options;

        return $this;
    }

    public function lookup(string $lookup): self
    {
        $minifier = collect(self::MINIFIERS)
            ->map(fn (string $minifier) => new $minifier)
            ->first(fn (MinifierInterface $minifier) => $this->supported($minifier, $lookup));

        return $this->minifier($minifier);
    }

    public function minifier(null|string|MinifierInterface $minifier = null): self
    {
        if (Types::string($minifier)) {
            return $this->minifier(new $minifier);
        }

        $this->minifier = $this->bootstrapped($minifier);

        return $this;
    }

    public function supported(MinifierInterface $minifier, string $lookup): bool
    {
        return collect(['mimeTypes', 'extensions'])
            ->map(fn (string $mode) => $minifier->$mode())
            ->flatten()
            ->toStringable()
            ->first(fn (Stringable $search) => $search->is($lookup)) !== null;
    }

    protected function bootstrapped(?MinifierInterface $minifier): MinifierInterface
    {
        return ($minifier ?? new Text)->bootstrap($this->contents, $this->options);
    }
}
