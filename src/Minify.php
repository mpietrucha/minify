<?php

namespace Mpietrucha\Minify;

use SplFileInfo;
use Mpietrucha\Support\Concerns\HasFactory;
use Mpietrucha\Support\Concerns\HasInputFile;
use Mpietrucha\Support\Types;
use Mpietrucha\Minify\Contracts\MinifierInterface;
use Mpietrucha\Minify\Minifier\Text;
use Mpietrucha\Minify\Minifier;
use Mpietrucha\Support\Macro;
use Illuminate\Support\Stringable;

class Minify
{
    use HasFactory;

    use HasInputFile {
        file as baseInputFile;
    }

    protected ?MinifierInterface $minifier;

    protected const MINIFIERS = [
        Minifier\Js::class,
        Minifier\Css::class,
        Minifier\Svg::class
    ];

    public function __construct(protected ?string $contents = null, protected array $options = [])
    {
        Macro::bootstrap();

        $this->minifier();
    }

    public static function file(SplFileInfo $file): ?self
    {
        return self::baseInputFile($file)?->lookup($file->getExtension());
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

        $this->bootstrap();

        return $this;
    }

    public function options(array $options = []): self
    {
        $this->options = $options;

        return $this;
    }

    public function lookup(string $lookup): self
    {
        $lookup = str($lookup);

        $minifier = collect(self::MINIFIERS)
            ->mapIntoInstance()
            ->first(fn (MinifierInterface $minifier) => $this->supported($minifier, $lookup));

        return $this->minifier($minifier);
    }

    public function minifier(null|string|MinifierInterface $minifier = null): self
    {
        if (Types::string($minifier)) {
            return $this->minifier(new $minifier);
        }

        $this->bootstrap($minifier);

        return $this;
    }

    public function supported(MinifierInterface $minifier, Stringable $lookup): bool
    {
        return collect(['mimeTypes', 'extensions'])
            ->map(fn (string $mode) => $minifier->$mode())
            ->flatten()
            ->first(fn (string $search) => $lookup->is($search)) !== null;
    }

    protected function bootstrap(?MinifierInterface $minifier = null): void
    {
        $this->minifier = ($minifier ?? new Text)->bootstrap($this->contents, $this->options);
    }
}
