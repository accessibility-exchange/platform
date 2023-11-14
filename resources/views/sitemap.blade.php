<?= '<' . '?' . 'xml version="1.0" encoding="UTF-8"?>' . "\n" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml"
    xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
    xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"
    xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    @foreach ($routes as $baseUrl => $localizedUrls)
        <url>
            @if ($baseUrl !== 'about/page/{page}')
                @foreach ($localizedUrls as $locale => $localizedUrl)
                    @if ($locale === 'en')
                        <loc>{{ env('APP_URL') . '/' . $localizedUrl }}</loc>
                        <lastMod>{{ $lastmod[$baseUrl] ?? $lastmod['default'] }}</lastMod>
                    @endif
                    <link hreflang="{{ $locale }}" href="{{ env('APP_URL') . '/' . $localizedUrl }}"
                        rel="alternate" />
                @endforeach
            @endif
        </url>
    @endforeach
</urlset>
