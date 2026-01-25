<?php

namespace MonstreX\VoyagerSite\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class SitemapService
{
    public function generate(): string
    {
        $cacheKey = 'voyager-site-sitemap';
        $cacheTtl = config('voyager-site.sitemap.cache_ttl', 3600);

        if ($cacheTtl > 0 && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $urls = $this->collectUrls();
        $xml = $this->buildXml($urls);

        if ($cacheTtl > 0) {
            Cache::put($cacheKey, $xml, $cacheTtl);
        }

        return $xml;
    }

    public function clearCache(): void
    {
        Cache::forget('voyager-site-sitemap');
    }

    protected function collectUrls(): array
    {
        $urls = [];
        $urls = array_merge($urls, $this->getStaticUrls());
        $urls = array_merge($urls, $this->getRouteModelUrls());
        $urls = array_merge($urls, $this->getSimpleRouteUrls());

        return $urls;
    }

    protected function getStaticUrls(): array
    {
        $urls = [];
        $static = config('voyager-site.sitemap.static', []);
        $baseUrl = rtrim(config('app.url'), '/');

        foreach ($static as $path => $options) {
            $urls[] = [
                'loc' => $baseUrl.$path,
                'lastmod' => $options['lastmod'] ?? null,
                'changefreq' => $options['changefreq'] ?? config('voyager-site.sitemap.defaults.changefreq', 'monthly'),
                'priority' => $options['priority'] ?? config('voyager-site.sitemap.defaults.priority', 0.5),
            ];
        }

        return $urls;
    }

    protected function getRouteModelUrls(): array
    {
        $urls = [];
        $routes = config('voyager-site.sitemap.routes', []);

        foreach ($routes as $routeName => $config) {
            if (!Route::has($routeName)) {
                continue;
            }

            $modelClass = $config['model'] ?? null;
            if (!$modelClass || !class_exists($modelClass)) {
                continue;
            }

            $query = $modelClass::query();

            if (!empty($config['scope'])) {
                $scope = $config['scope'];
                $query->$scope();
            }

            if (!empty($config['status'])) {
                $statusField = $config['status']['field'] ?? 'status';
                $statusValue = $config['status']['value'] ?? 1;
                if (is_array($statusValue)) {
                    $query->whereIn($statusField, $statusValue);
                } else {
                    $query->where($statusField, $statusValue);
                }
            }

            if (!empty($config['with'])) {
                $query->with($config['with']);
            }

            $records = $query->get();
            $field = $config['field'] ?? 'slug';
            $lastmodField = $config['lastmod'] ?? 'updated_at';

            foreach ($records as $record) {
                $params = [];

                if (!empty($config['params'])) {
                    foreach ($config['params'] as $param => $fieldPath) {
                        $params[$param] = data_get($record, $fieldPath);
                    }
                } else {
                    $param = $config['param'] ?? 'slug';
                    $params[$param] = $record->{$field};
                }

                try {
                    $url = route($routeName, $params);
                } catch (\Exception $e) {
                    continue;
                }

                $path = parse_url($url, PHP_URL_PATH) ?? '/';
                if ($this->isExcluded($path, config('voyager-site.sitemap.exclude', []))) {
                    continue;
                }

                $lastmod = null;
                if ($lastmodField && isset($record->{$lastmodField})) {
                    $date = $record->{$lastmodField};
                    $lastmod = $date instanceof \DateTimeInterface
                        ? $date->format('Y-m-d')
                        : (is_string($date) ? substr($date, 0, 10) : null);
                }

                $urls[] = [
                    'loc' => $url,
                    'lastmod' => $lastmod,
                    'changefreq' => $config['changefreq'] ?? config('voyager-site.sitemap.defaults.changefreq', 'monthly'),
                    'priority' => $config['priority'] ?? config('voyager-site.sitemap.defaults.priority', 0.5),
                ];
            }
        }

        return $urls;
    }

    protected function getSimpleRouteUrls(): array
    {
        if (!config('voyager-site.sitemap.include_simple_routes', false)) {
            return [];
        }

        $urls = [];
        $exclude = config('voyager-site.sitemap.exclude', []);
        $excludeNames = config('voyager-site.sitemap.exclude_names', []);
        $configuredRoutes = array_keys(config('voyager-site.sitemap.routes', []));
        $baseUrl = rtrim(config('app.url'), '/');

        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            if (!in_array('GET', $route->methods(), true)) {
                continue;
            }

            $uri = $route->uri();
            $name = $route->getName();

            if (preg_match('/\{[^}]+\}/', $uri)) {
                continue;
            }

            if ($name && in_array($name, $configuredRoutes, true)) {
                continue;
            }

            if ($this->isExcluded($uri, $exclude)) {
                continue;
            }

            if ($name && $this->isExcludedName($name, $excludeNames)) {
                continue;
            }

            $urls[] = [
                'loc' => $baseUrl.'/'.ltrim($uri, '/'),
                'lastmod' => null,
                'changefreq' => config('voyager-site.sitemap.defaults.changefreq', 'monthly'),
                'priority' => config('voyager-site.sitemap.defaults.priority', 0.5),
            ];
        }

        return $urls;
    }

    protected function isExcluded(string $uri, array $patterns): bool
    {
        $uri = '/'.ltrim($uri, '/');

        foreach ($patterns as $pattern) {
            $pattern = '/'.ltrim($pattern, '/');

            if (str_contains($pattern, '*')) {
                $regex = '/^'.str_replace('\*', '.*', preg_quote($pattern, '/')).'$/';
                if (preg_match($regex, $uri)) {
                    return true;
                }
            } elseif ($uri === $pattern) {
                return true;
            }
        }

        return false;
    }

    protected function isExcludedName(string $name, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (str_contains($pattern, '*')) {
                $regex = '/^'.str_replace('\*', '.*', preg_quote($pattern, '/')).'$/';
                if (preg_match($regex, $name)) {
                    return true;
                }
            } elseif ($name === $pattern) {
                return true;
            }
        }

        return false;
    }

    protected function buildXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>".htmlspecialchars($url['loc'])."</loc>\n";

            if (!empty($url['lastmod'])) {
                $xml .= "        <lastmod>{$url['lastmod']}</lastmod>\n";
            }

            if (!empty($url['changefreq'])) {
                $xml .= "        <changefreq>{$url['changefreq']}</changefreq>\n";
            }

            if (isset($url['priority'])) {
                $xml .= "        <priority>{$url['priority']}</priority>\n";
            }

            $xml .= "    </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
