<?php

namespace Lareon\Modules\Captcha\App\Services;

use ReflectionClass;

/**
 * Immutable set of options for one captcha preset.
 *
 * Only the keys declared in the constructor are accepted from config,
 * so a config file (or a URL segment) can never overwrite internal state.
 */
class CaptchaOptions
{
    /** @var string[]|null */
    private static ?array $knownKeys = null;

    /**
     * @param string[]|string|null $characters Alphabet override for this preset (null = global alphabet)
     * @param string[]             $fontColors Hex colors; empty = random dark colors
     */
    public function __construct(
        public readonly int $length = 5,
        public readonly int $width = 120,
        public readonly int $height = 36,
        public readonly int $quality = 90,
        public readonly bool $math = false,
        public readonly bool $sensitive = false,
        public readonly int $expire = 180,
        public readonly int $angle = 15,
        public readonly int $lines = 3,
        public readonly int $lineWidth = 1,
        public readonly string $lineColor = '#ff00ff',
        public readonly int $noise = 25,
        public readonly bool $bgImage = true,
        public readonly string $bgColor = '#ffffff',
        public readonly string $fill = '#cccccc',
        public readonly array $fontColors = [],
        public readonly int $contrast = 0,
        public readonly int $sharpen = 0,
        public readonly int $blur = 0,
        public readonly bool $invert = false,
        public readonly int $marginTop = 0,
        public readonly int $textLeftPadding = 4,
        public readonly array|string|null $characters = null,
    ) {}

    /**
     * Build options from a config array. Unknown keys are ignored and
     * numeric values are clamped to safe ranges.
     */
    public static function fromArray(array $values,): static
    {
        $values = array_intersect_key($values, array_flip(self::knownKeys()));

        $ranges = [
            'length'  => [1, 16],
            'width'   => [40, 600],
            'height'  => [20, 300],
            'quality' => [30, 100],
            'expire'  => [10, 86400],
            'lines'   => [0, 30],
            'noise'   => [0, 2000],
        ];

        foreach ($ranges as $key => [$min, $max]) {
            if (isset($values[$key])) $values[$key] = max($min, min($max, (int)$values[$key]));
        }
        return new static(...$values);
    }

    /**
     * @return string[]
     */
    private static function knownKeys(): array
    {
        return self::$knownKeys ??= array_map(
            fn($parameter,) => $parameter->getName(),
            (new ReflectionClass(self::class))->getConstructor()->getParameters()
        );
    }
}
