<?php

namespace Lorisleiva\Vary;

use Exception;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Vary\Concerns\AltersLines;
use Lorisleiva\Vary\Concerns\AltersMethods;
use Lorisleiva\Vary\Concerns\AltersMustaches;
use Lorisleiva\Vary\Concerns\AltersProperties;
use Lorisleiva\Vary\Concerns\AltersWhitespace;
use Lorisleiva\Vary\Concerns\ProvidesAccessors;
use Lorisleiva\Vary\Concerns\ProvidesFragments;
use Lorisleiva\Vary\Concerns\ProvidesMutators;
use Symfony\Component\VarDumper\VarDumper;

class Variant
{
    // Extendable.
    use Conditionable;
    use Macroable;

    // Core Traits.
    use ProvidesFragments;
    use ProvidesMutators;
    use ProvidesAccessors;

    // Syntactic sugar.
    use AltersMustaches;
    use AltersWhitespace;
    use AltersLines;
    use AltersProperties;
    use AltersMethods;

    protected string $value;
    protected ?string $path;

    public function __construct(string $value, ?string $path = null)
    {
        $this->value = $value;
        $this->path = $path;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function toString(): string
    {
        return $this->value;
    }

    #[Pure] public function __toString(): string
    {
        return $this->toString();
    }

    #[Pure] public function new(string $value): static
    {
        return new static($value, $this->path);
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
