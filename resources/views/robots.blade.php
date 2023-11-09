<?php header('Content-Type: text/plain; charset=utf-8'); ?>
Sitemap: {{ env('APP_URL') }}/sitemap.xml

User-Agent: *
Disallow: admin/
Disallow: api/
Disallow: status/

User-Agent: GPTBot
Disallow: /
