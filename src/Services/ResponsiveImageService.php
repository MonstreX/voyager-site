<?php

namespace MonstreX\VoyagerSite\Services;

use Illuminate\Support\HtmlString;
use VData;

class ResponsiveImageService
{
    /**
     * Default breakpoint multipliers for srcset generation.
     *
     * @var float[]
     */
    protected array $defaultMultipliers = [0.5, 1, 1.5, 2];

    protected string $defaultFormat = 'webp';
    protected int $defaultQuality = 80;

    /**
     * Generate responsive <img> tag with srcset.
     */
    public function image(string $src, int $width, ?int $height = null, array $options = []): HtmlString
    {
        $format = $options['format'] ?? $this->defaultFormat;
        $quality = $options['quality'] ?? $this->defaultQuality;
        $alt = $options['alt'] ?? '';
        $class = $options['class'] ?? '';
        $lazy = $options['lazy'] ?? true;
        $strict = $options['strict'] ?? true;
        $sizes = $options['sizes'] ?? $this->generateDefaultSizes($width);
        $srcsetWidths = $options['srcset'] ?? $this->generateSrcsetWidths($width);

        if ($strict) {
            $options['formats'] = $options['formats'] ?? [$format];
            $options['fallback'] = $options['fallback'] ?? $format;
            $options['strict'] = true;
            return $this->picture($src, $width, $height, $options);
        }

        $mainSrc = VData::getImageOrCreate($src, $width, $height, $format, $quality);
        $srcset = $this->generateSrcset($src, $width, $height, $srcsetWidths, $format, $quality);

        $attrs = [
            'src' => $mainSrc,
            'srcset' => $srcset,
            'sizes' => $sizes,
            'width' => $width,
            'alt' => $alt,
        ];

        if ($height) {
            $attrs['height'] = $height;
        }

        if ($lazy) {
            $attrs['loading'] = 'lazy';
        }

        if ($class) {
            $attrs['class'] = $class;
        }

        foreach ($options as $key => $value) {
            if (!in_array($key, ['format', 'quality', 'alt', 'class', 'lazy', 'sizes', 'srcset', 'strict', 'formats', 'fallback'], true)) {
                $attrs[$key] = $value;
            }
        }

        return new HtmlString($this->buildTag('img', $attrs));
    }

    /**
     * Generate responsive <picture> tag with WebP and fallback formats.
     */
    public function picture(string $src, int $width, ?int $height = null, array $options = []): HtmlString
    {
        $quality = $options['quality'] ?? $this->defaultQuality;
        $alt = $options['alt'] ?? '';
        $class = $options['class'] ?? '';
        $lazy = $options['lazy'] ?? true;
        $sizes = $options['sizes'] ?? $this->generateDefaultSizes($width);
        $srcsetWidths = $options['srcset'] ?? $this->generateSrcsetWidths($width);
        $breakpoints = $options['breakpoints'] ?? [];
        $strict = $options['strict'] ?? true;
        $formats = $options['formats'] ?? ['webp'];
        $fallbackFormat = $options['fallback'] ?? $this->detectOriginalFormat($src);

        $html = "<picture>\n";

        if (empty($breakpoints) && $strict) {
            $breakpoints = $this->generateAutoBreakpoints($width, $height, $srcsetWidths);
        }

        foreach ($breakpoints as $bp) {
            $bpWidth = $bp['width'];
            $bpHeight = $bp['height'] ?? null;
            $media = $bp['media'];
            $bpSrcsetWidths = $bp['srcset'] ?? $this->generateSrcsetWidths($bpWidth);

            foreach ($formats as $format) {
                $bpSrcset = $this->generateSrcset($src, $bpWidth, $bpHeight, $bpSrcsetWidths, $format, $quality);
                $html .= "    <source media=\"{$media}\" type=\"image/{$format}\" srcset=\"{$bpSrcset}\">\n";
            }
        }

        if (!$strict) {
            foreach ($formats as $format) {
                $srcset = $this->generateSrcset($src, $width, $height, $srcsetWidths, $format, $quality);
                $html .= "    <source type=\"image/{$format}\" srcset=\"{$srcset}\" sizes=\"{$sizes}\">\n";
            }
        }

        $fallbackSrc = VData::getImageOrCreate($src, $width, $height, $fallbackFormat, $quality);

        $imgAttrs = [
            'src' => $fallbackSrc,
            'width' => $width,
            'alt' => $alt,
        ];

        if ($height) {
            $imgAttrs['height'] = $height;
        }

        if ($lazy) {
            $imgAttrs['loading'] = 'lazy';
        }

        if ($class) {
            $imgAttrs['class'] = $class;
        }

        $html .= "    " . $this->buildTag('img', $imgAttrs) . "\n";
        $html .= "</picture>";

        return new HtmlString($html);
    }

    protected function generateSrcset(
        string $src,
        int $baseWidth,
        ?int $baseHeight,
        array $widths,
        string $format,
        int $quality
    ): string {
        $srcset = [];

        foreach ($widths as $w) {
            $h = $baseHeight ? (int) round($baseHeight * ($w / $baseWidth)) : null;

            $url = VData::getImageOrCreate($src, $w, $h, $format, $quality);
            $srcset[] = "{$url} {$w}w";
        }

        return implode(', ', $srcset);
    }

    protected function generateSrcsetWidths(int $baseWidth): array
    {
        $widths = [];

        foreach ($this->defaultMultipliers as $multiplier) {
            $w = (int) round($baseWidth * $multiplier);
            if ($w <= 2400 && $w >= 100 && $w <= $baseWidth) {
                $widths[] = $w;
            }
        }

        $widths = array_unique($widths);
        sort($widths);

        return $widths;
    }

    protected function generateDefaultSizes(int $width): string
    {
        $sizes = [];
        $breakpoints = $this->generateSrcsetWidths($width);

        foreach ($breakpoints as $i => $bp) {
            if ($i < count($breakpoints) - 1) {
                $sizes[] = "(max-width: {$bp}px) {$bp}px";
            } else {
                $sizes[] = "{$bp}px";
            }
        }

        return implode(', ', $sizes);
    }

    protected function generateAutoBreakpoints(int $baseWidth, ?int $baseHeight, array $widths): array
    {
        $breakpoints = [];
        $widths = array_values($widths);
        sort($widths);

        $last = count($widths) - 1;
        foreach ($widths as $index => $width) {
            if ($index === $last) {
                continue;
            }

            $height = $baseHeight ? (int) round($baseHeight * ($width / $baseWidth)) : null;

            $breakpoints[] = [
                'media' => "(max-width: {$width}px)",
                'width' => $width,
                'height' => $height,
                'srcset' => [$width],
            ];
        }

        return $breakpoints;
    }

    protected function detectOriginalFormat(string $src): string
    {
        $ext = strtolower(pathinfo($src, PATHINFO_EXTENSION));

        return match ($ext) {
            'png' => 'png',
            'gif' => 'gif',
            'webp' => 'webp',
            default => 'jpg',
        };
    }

    protected function buildTag(string $tag, array $attrs): string
    {
        $attrStr = [];

        foreach ($attrs as $key => $value) {
            if ($value === true) {
                $attrStr[] = $key;
            } elseif ($value !== false && $value !== null && $value !== '') {
                $attrStr[] = $key.'="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'"';
            }
        }

        $selfClosing = in_array($tag, ['img', 'input', 'br', 'hr', 'meta', 'link'], true);

        if ($selfClosing) {
            return "<{$tag} ".implode(' ', $attrStr).">";
        }

        return "<{$tag} ".implode(' ', $attrStr)."></{$tag}>";
    }
}
