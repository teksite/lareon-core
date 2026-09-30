<?php

namespace Lareon\Modules\Captcha\App\Services;


use GdImage;
use RuntimeException;

/**
 * Lightweight image helper built only on PHP's GD extension (with FreeType).
 * No third-party package is required.
 */
class GdImageService
{
    protected GdImage $im;
    protected int     $width;
    protected int     $height;

    protected function __construct(GdImage $im, int $width, int $height)
    {
        $this->im = $im;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Create a new blank canvas filled with a color.
     */
    public static function canvas(int $width, int $height, string $fill = '#ffffff'): static
    {
        if (!extension_loaded('gd') || !function_exists('imagettftext'))
            throw new RuntimeException('GD extension with FreeType support is required.');

        $im = imagecreatetruecolor($width, $height);

        if ($im === false) throw new RuntimeException('Unable to create image canvas.');

        $instance = new static($im, $width, $height);

        return $instance->fill($fill);
    }

    public function width(): int
    {
        return $this->width;
    }

    public function height(): int
    {
        return $this->height;
    }

    /**
     * Fill the whole canvas with a color.
     */
    public function fill(string $color): static
    {
        imagefilledrectangle(
            $this->im,
            0,
            0,
            $this->width - 1,
            $this->height - 1,
            $this->allocate($color)
        );

        return $this;
    }

    /**
     * Load an image file (jpg/png/gif/webp), resize it to the canvas size and draw it on the canvas.
     */
    public function placeBackground(string $path): static
    {
        $data = @file_get_contents($path);

        if ($data === false) throw new RuntimeException("Unable to read background image: $path");

        $src = @imagecreatefromstring($data);
        if ($src === false) throw new RuntimeException("Invalid background image: $path");

        imagecopyresampled(
            $this->im,
            $src,
            0,
            0,
            0,
            0,
            $this->width,
            $this->height,
            imagesx($src),
            imagesy($src)
        );

        return $this;
    }

    /**
     * Draw text. $x / $y refer to the top-left corner of the character (before rotation).
     *
     * @param int $sizePx Font size in pixels
     * @param int $angle  Counter-clockwise rotation in degrees
     */
    public function text(string $text, int $x, int $y, string $fontFile, int $sizePx, string $color, int $angle = 0): static
    {
        $size = $sizePx * 0.75; // GD uses points, not pixels

        $box = imagettfbbox($size, 0, $fontFile, $text);
        if ($box === false) throw new RuntimeException("Unable to use font: {$fontFile}");


        // imagettftext expects the baseline position, so shift down by the ascent (valign: top)
        $baseline = (int)round($y - $box[7]);

        $result = imagettftext(
            $this->im,
            $size,
            $angle,
            $x,
            $baseline,
            $this->allocate($color),
            $fontFile,
            $text
        );

        if ($result === false) throw new RuntimeException("Unable to draw text with font: {$fontFile}");

        return $this;
    }

    /**
     * Draw a line.
     */
    public function line(int $x1, int $y1, int $x2, int $y2, string $color, int $thickness = 1): static
    {
        imagesetthickness($this->im, max(1, $thickness));
        imageline($this->im, $x1, $y1, $x2, $y2, $this->allocate($color));
        imagesetthickness($this->im, 1);

        return $this;
    }

    /**
     * Draw a single pixel (used for noise).
     */
    public function dot(int $x, int $y, string $color): static
    {
        imagesetpixel($this->im, $x, $y, $this->allocate($color));

        return $this;
    }

    /**
     * Contrast level from -100 to 100 (positive = more contrast).
     */
    public function contrast(int $level): static
    {
        // GD's scale is inverted compared to the common convention
        imagefilter($this->im, IMG_FILTER_CONTRAST, -max(-100, min(100, $level)));

        return $this;
    }

    /**
     * Sharpen with amount from 0 to 100.
     */
    public function sharpen(int $amount): static
    {
        $k = max(0, min(100, $amount)) / 50;
        $matrix = [
            [0, -$k, 0],
            [-$k, 1 + 4 * $k, -$k],
            [0, -$k, 0],
        ];
        imageconvolution($this->im, $matrix, 1, 0);

        return $this;
    }

    /**
     * Blur with amount from 0 to 100 (applied as repeated gaussian passes, capped at 10).
     */
    public function blur(int $amount): static
    {
        $passes = max(1, min(10, (int)ceil($amount / 10)));
        for ($i = 0; $i < $passes; $i++) {
            imagefilter($this->im, IMG_FILTER_GAUSSIAN_BLUR);
        }

        return $this;
    }

    /**
     * Invert colors.
     */
    public function invert(): static
    {
        imagefilter($this->im, IMG_FILTER_NEGATE);
        return $this;
    }

    /**
     * Get the image as JPEG binary string.
     */
    public function toJpeg(int $quality = 90): string
    {
        ob_start();
        imagejpeg($this->im, null, $quality);

        return (string)ob_get_clean();
    }

    /**
     * Get the image as a data URI (base64 JPEG).
     */
    public function toDataUri(int $quality = 90): string
    {
        return 'data:image/jpeg;base64,'.base64_encode($this->toJpeg($quality));
    }

    /**
     * Convert '#fff', 'fff', '#ffffff' or 'ffffff' to a GD color.
     */
    protected function allocate(string $color): int
    {
        [$r, $g, $b] = $this->parseColor($color);

        return imagecolorallocate($this->im, $r, $g, $b);
    }

    protected function parseColor(string $color): array
    {
        $hex = ltrim(trim($color), '#');

        if (strlen($hex) === 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];

        if (!preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
            throw new RuntimeException("Invalid color: $color");
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
