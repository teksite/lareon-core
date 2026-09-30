<?php

namespace Lareon\Modules\Captcha\App\Services;

use Random\RandomException;
use RuntimeException;

/**
 * Draws a captcha image. It knows nothing about tokens, cache or validation:
 * text + options in, JPEG bytes out.
 */
class CaptchaRenderer
{
    /** @var string[]|null */
    private ?array $fonts = null;

    /** @var string[]|null */
    private ?array $backgrounds = null;

    public function __construct(private readonly string $fontsDirectory, private readonly string $backgroundsDirectory) {}

    /**
     * @return string JPEG binary
     * @throws RandomException
     */
    public function render(CaptchaOptions $options, string $text): string
    {
        $image = GdImageService::canvas(
            $options->width,
            $options->height,
            $options->bgImage ? $options->fill : $options->bgColor
        );

        if ($options->bgImage) $image->placeBackground($this->randomItem($this->backgrounds(), 'background image'));

        if ($options->contrast !== 0) $image->contrast($options->contrast);

        $this->drawText($image, $options, $text);
        $this->drawNoise($image, $options);
        $this->drawLines($image, $options);

        if ($options->sharpen > 0) $image->sharpen($options->sharpen);

        if ($options->invert) $image->invert();

        if ($options->blur > 0) $image->blur($options->blur);

        return $image->toJpeg($options->quality);
    }

    /**
     * Draw characters left to right. The font size is limited by the width of
     * each cell so that long codes on narrow images stay readable.
     *
     * @throws RandomException
     */
    private function drawText(GdImageService $image, CaptchaOptions $options, string $text): void
    {
        $chars = mb_str_split($text);
        $count = max(1, count($chars));

        $padding = $options->textLeftPadding;
        $cellWidth = ($options->width - 2 * $padding) / $count;

        $maxSize = max(10, min((int)($options->height * 0.8), (int)($cellWidth * 1.4)));
        $minSize = max(10, min((int)($options->height * 0.6), $maxSize));

        foreach ($chars as $index => $char) {
            $size = random_int($minSize, $maxSize);

            // glyph height is roughly 0.75 * font size
            $freeSpace = (int)($options->height - $size * 0.75 - 2);
            $top = $options->marginTop !== 0
                ? $options->marginTop
                : random_int(2, max(2, $freeSpace));

            $image->text(
                $char,
                (int)($padding + $index * $cellWidth),
                $top,
                $this->randomItem($this->fonts(), 'font'),
                $size,
                $this->fontColor($options),
                random_int(-$options->angle, $options->angle)
            );
        }
    }

    /**
     * @throws RandomException
     */
    private function drawNoise(GdImageService $image, CaptchaOptions $options): void
    {
        for ($i = 0; $i < $options->noise; $i++) {
            $image->dot(
                random_int(0, $options->width - 1),
                random_int(0, $options->height - 1),
                $this->darkColor()
            );
        }
    }

    /**
     * @throws RandomException
     */
    private function drawLines(GdImageService $image, CaptchaOptions $options): void
    {
        for ($i = 0; $i < $options->lines; $i++) {
            $image->line(
                random_int(0, $options->width),
                random_int(0, $options->height),
                random_int(0, $options->width),
                random_int(0, $options->height),
                $options->lineColor,
                $options->lineWidth
            );
        }
    }

    /**
     * @throws RandomException
     */
    private function fontColor(CaptchaOptions $options): string
    {
        if ($options->fontColors !== [])  return $options->fontColors[array_rand($options->fontColors)];

        return $this->darkColor();
    }

    /**
     * Random color that stays readable on light backgrounds.
     *
     * @throws RandomException
     */
    private function darkColor(): string
    {
        return sprintf('#%02x%02x%02x', random_int(0, 150), random_int(0, 150), random_int(0, 150));
    }

    /**
     * @param string[] $items
     * @throws RandomException
     */
    private function randomItem(array $items, string $label): string
    {
        if ($items === []) throw new RuntimeException("Captcha: no $label available.");

        return $items[random_int(0, count($items) - 1)];
    }

    /**
     * @return string[]
     */
    private function fonts(): array
    {
        return $this->fonts ??= $this->listFiles($this->fontsDirectory, ['ttf', 'otf']);
    }

    /**
     * @return string[]
     */
    private function backgrounds(): array
    {
        return $this->backgrounds ??= $this->listFiles($this->backgroundsDirectory, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    /**
     * Files of a directory filtered by extension (ignores .gitkeep, .DS_Store, license files...).
     * The result is memoized for the lifetime of this object.
     *
     * @param string[] $extensions
     * @return string[]
     */
    private function listFiles(string $directory, array $extensions): array
    {
        $files = [];

        foreach (glob(rtrim($directory, '/\\').DIRECTORY_SEPARATOR.'*') ?: [] as $path) {
            if (is_file($path) && in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $extensions, true)) {
                $files[] = $path;
            }
        }

        sort($files);

        return $files;
    }
}
