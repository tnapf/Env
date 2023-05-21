<?php

namespace Tnapf\Env;

use ArrayAccess;
use LogicException;
use RuntimeException;

use function count;
use function explode;
use function preg_match;
use function strlen;
use function trim;

class Env implements ArrayAccess
{
    protected static ?self $instance = null;

    protected array $props = [];

    /**
     * @throws LogicException If an Environment has already been created
     */
    public function __construct()
    {
        if (self::$instance !== null) {
            throw new LogicException('An Environment has already been substantiated!');
        }

        self::$instance = $this;
    }

    public static function get(): ?self
    {
        return self::$instance ?? null;
    }

    /**
     * @throws RuntimeException If the file does not exist
     */
    public static function createFromFile(string $path): self
    {
        if (!file_exists($path)) {
            throw new RuntimeException("{$path} is not an existing env file!");
        }

        return self::createFromString(file_get_contents($path));
    }

    public static function createFromString(string $contents): self
    {
        $instance = new self();
        $lines = explode("\n", $contents);

        foreach ($lines as $number => $line) {
            if (!strlen(trim($line))) {
                continue;
            }

            $number++; // make line number proper

            $parts = explode('=', $line);

            if (count($parts) !== 2) {
                throw new RuntimeException("Line {$number} is not correct");
            }

            $name = trim($parts[0]);
            $value = trim($parts[1]);

            if (preg_match('/\W/', $name)) {
                throw new RuntimeException("{$name} is not valid on line {$number}");
            }

            $instance->__set($name, $value);
        }

        return $instance;
    }

    public static function destroy(): void
    {
        self::$instance = null;
    }

    public function __debugInfo()
    {
        return $this->props;
    }

    public function __set(mixed $name, mixed $value): void
    {
        $this->props[$name] = $value;
    }

    public function __unset(mixed $name): void
    {
        unset($this->props[$name]);
    }

    public function __get(mixed $name): mixed
    {
        return $this->props[$name] ?? null;
    }

    public function __isset(mixed $name): bool
    {
        return isset($this->props[$name]);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->__get($offset) !== null;
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->__unset($offset);
    }
}
