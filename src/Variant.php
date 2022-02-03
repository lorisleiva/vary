<?php

namespace Lorisleiva\Vary;

use Exception;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Tappable;
use JetBrains\PhpStorm\NoReturn;
use Lorisleiva\Vary\Blocks\Block;
use Lorisleiva\Vary\Concerns\AltersLines;
use Lorisleiva\Vary\Concerns\AltersMustaches;
use Lorisleiva\Vary\Concerns\AltersPhpFiles;
use Lorisleiva\Vary\Concerns\AltersWhitespace;
use Lorisleiva\Vary\Concerns\ProvidesAccessors;
use Lorisleiva\Vary\Concerns\ProvidesFragments;
use Lorisleiva\Vary\Concerns\ProvidesMutators;
use Symfony\Component\VarDumper\VarDumper;

class Variant
{
    // Extendable.
    use Conditionable;
    use Tappable;
    use Macroable;

    // Core Traits.
    use ProvidesFragments;
    use ProvidesMutators;
    use ProvidesAccessors;

    // Syntactic sugar.
    use AltersMustaches;
    use AltersWhitespace;
    use AltersLines;
    use AltersPhpFiles;

    protected string $value;
    protected ?string $path;

    public function __construct(string $value, ?string $path = null)
    {
        $this->value = $value;
        $this->path = $path;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function new(string $value): static
    {
        return new static($value, $this->path);
    }

    public function block(string $pattern, string $allowedPattern = '\s*'): Block
    {
        return new Block($this, $pattern, $allowedPattern);
    }

    public function save(?string $path = null, int $flags = 0): static
    {
        if (! $path = $path ?? $this->path) {
            throw new Exception('Path not given');
        }

        file_put_contents($path, $this->value, $flags) !== false;

        return $this;
    }

    public function saveAs(string $path, int $flags = 0): static
    {
        return $this->save($path, $flags);
    }

    public function dump(): static
    {
        VarDumper::dump($this->value);

        return $this;
    }

    #[NoReturn] public function dd(): void
    {
        $this->dump();

        exit(1);
    }
}
