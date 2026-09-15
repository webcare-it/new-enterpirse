<?php

namespace App\Http\Controllers;

use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\Blog;
use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = rtrim(config('app.url'), '/');

        $allUrls = array_merge(
            $this->getStaticUrls($baseUrl),
            $this->getProductUrls($baseUrl),
            $this->getCategoryUrls($baseUrl),
            $this->getBlogUrls($baseUrl),
            $this->getPageUrls($baseUrl),
        );

        $xml = $this->buildXml($allUrls);

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    private function getStaticUrls(string $baseUrl): array
    {
        return [
            ['loc' => $baseUrl . '/',             'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => $baseUrl . '/products',     'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => $baseUrl . '/blogs',        'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl . '/about-us',     'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl . '/contact-us',   'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl . '/faqs',         'priority' => '0.5', 'changefreq' => 'monthly'],
        ];
    }

    private function getProductUrls(string $baseUrl): array
    {
        $products = Product::select('slug', 'updated_at')
            ->whereNotNull('slug')
            ->where('is_published', 1)
            ->where('status', 1)
            ->get();

        return $products->map(fn($p) => [
            'loc'        => $baseUrl . '/products/' . $p->slug,
            'lastmod'    => $p->updated_at?->toAtomString(),
            'priority'   => '0.8',
            'changefreq' => 'weekly',
        ])->toArray();
    }

    private function getCategoryUrls(string $baseUrl): array
    {
        $categories = Category::select('slug', 'updated_at')
            ->whereNotNull('slug')
            ->get();

        return $categories->map(fn($c) => [
            'loc'        => $baseUrl . '/categories/' . $c->slug,
            'lastmod'    => $c->updated_at?->toAtomString(),
            'priority'   => '0.7',
            'changefreq' => 'weekly',
        ])->toArray();
    }

    private function getBlogUrls(string $baseUrl): array
    {
        $blogs = Blog::select('slug', 'updated_at')
            ->whereNotNull('slug')
            ->get();

        return $blogs->map(fn($b) => [
            'loc'        => $baseUrl . '/blogs/' . $b->slug,
            'lastmod'    => $b->updated_at?->toAtomString(),
            'priority'   => '0.7',
            'changefreq' => 'weekly',
        ])->toArray();
    }

    private function getPageUrls(string $baseUrl): array
    {
        $excludedSlugs = [
            'home', 'about-us', 'contact-us', 'blogs',
            'return_policy', 'refund_policy',
        ];

        $pages = Page::select('slug', 'updated_at')
            ->whereNotNull('slug')
            ->whereNotIn('slug', $excludedSlugs)
            ->get();

        return $pages->map(fn($p) => [
            'loc'        => $baseUrl . '/pages/' . $p->slug,
            'lastmod'    => $p->updated_at?->toAtomString(),
            'priority'   => '0.5',
            'changefreq' => 'monthly',
        ])->toArray();
    }

    private function buildXml(array $urls): string
    {
        $items = '';
        foreach ($urls as $url) {
            $items .= "\n    <url>";
            $items .= "\n        <loc>" . htmlspecialchars($url['loc']) . "</loc>";
            if (!empty($url['lastmod'])) {
                $items .= "\n        <lastmod>" . $url['lastmod'] . "</lastmod>";
            }
            if (!empty($url['changefreq'])) {
                $items .= "\n        <changefreq>" . $url['changefreq'] . "</changefreq>";
            }
            if (!empty($url['priority'])) {
                $items .= "\n        <priority>" . $url['priority'] . "</priority>";
            }
            $items .= "\n    </url>";
        }

        return '<?xml version="1.0" encoding="UTF-8"?>' .
            "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' .
            $items .
            "\n" . '</urlset>';
    }
}
