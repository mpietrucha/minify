<?php

namespace Mpietrucha\Minify\Minifiers;

class Svg extends Text
{
    protected const OPENING = '<svg';
    protected const NAMESPACE_URI_DELIMITERS = ['xmlns="', '"'];

    public function mimeTypes(): array
    {
        return ['image/svg*'];
    }

    public function extensions(): array
    {
        return ['svg'];
    }

    public function minify(): string
    {
        [$namespaceStart, $namespaceEnd] = self::NAMESPACE_URI_DELIMITERS;

        return str(parent::minify())->explode(self::OPENING)->filter()->toStringable()->map->trim()->prepend(
            str($this->contents)->toBetweenCollection($namespaceStart, $namespaceEnd)
                ->toStringable()
                ->first()
                ->prepend($namespaceStart)
                ->append($namespaceEnd)
        )->prepend(self::OPENING)->toWords();
    }
}
