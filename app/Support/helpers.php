<?php

use App\Event;

if (!function_exists('current_event_id')) {
    /**
     * Returns the id of the event currently selected in the admin session.
     */
    function current_event_id()
    {
        // Prioritize the active event in the database
        $event = Event::where('is_active', 1)->first();

        if ($event) {
            session(['current_event_id' => $event->id]);
            return (int) $event->id;
        }

        $id = session('current_event_id');

        if ($id && Event::find($id)) {
            return (int) $id;
        }

        $event = Event::orderBy('id')->first();

        if ($event) {
            session(['current_event_id' => $event->id]);

            return (int) $event->id;
        }

        return null;
    }
}

if (!function_exists('current_event')) {
    /**
     * Returns the Event model currently selected in the admin session.
     */
    function current_event()
    {
        return Event::find(current_event_id());
    }
}

if (!function_exists('repair_json')) {
    /**
     * Escapes literal control characters (line breaks / tabs) that appear
     * inside JSON string values but were never escaped (e.g. content pasted
     * into a textarea as {"vi":"...\n..."} with real newlines). This makes
     * such malformed values decodable by json_decode.
     */
    function repair_json($value)
    {
        return preg_replace_callback(
            '/"(?:[^"\\\\]|\\\\.)*"/s',
            function ($m) {
                return str_replace(["\r\n", "\r", "\n", "\t"], ['\\r\\n', '\\r', '\\n', '\\t'], $m[0]);
            },
            $value
        );
    }
}

if (!function_exists('parse_locale_json')) {
    /**
     * Attempts to decode a value as a multilingual JSON object
     * (e.g. {"vi":"...","en":"..."}). Handles two common corruption cases:
     *  1. Literal line breaks inside JSON string values that were never escaped.
     *  2. JSON that was pasted into a rich-text editor and got wrapped in HTML
     *     tags (e.g. <p>{"vi":"</p><h2>content</h2><p>...</p><p>"}</p>).
     */
    function parse_locale_json($value)
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        $candidates = [$value];

        if (strpos($value, '<') !== false) {
            $plain = preg_replace('/<\/?(?:p|h[1-6]|li|div|br|ul|ol|section|article)\b[^>]*>/i', "\n", $value);
            $plain = trim(strip_tags($plain));
            if ($plain !== '' && $plain !== trim($value)) {
                $candidates[] = $plain;
            }
        }

        foreach ($candidates as $candidate) {
            $decoded = json_decode($candidate, true);
            if (!is_array($decoded)) {
                $decoded = json_decode(repair_json($candidate), true);
            }
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }
}

if (!function_exists('tr')) {
    /**
     * Translates a DB-stored value for the current locale.
     *
     * Accepts either a JSON object keyed by locale (e.g. {"vi": "...", "en": "..."}),
     * in which case the matching locale value is returned as-is, or a plain string,
     * which is auto-translated to the current locale using Google Translate
     * (falling back to the original text when translation is unavailable).
     */
    function tr($value, $fallback = '')
    {
        $locale = app()->getLocale();

        if ($value === null) {
            return $fallback;
        }

        if (is_array($value)) {
            return $value[$locale] ?? $value['en'] ?? $fallback;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return $fallback;
        }

        $decoded = parse_locale_json($value);
        if (is_array($decoded)) {
            return $decoded[$locale] ?? $decoded['en'] ?? $fallback;
        }

        return auto_translate($value, $locale) ?: $value ?: $fallback;
    }
}

if (!function_exists('auto_translate')) {
    /**
     * Auto-translates a plain-text value into $target using Google's free
     * translation endpoint. Results are cached to avoid repeated calls and
     * fall back to the original text when the service is unreachable.
     */
    function auto_translate($text, $target = 'en')
    {
        $text = trim((string) $text);
        if ($text === '') {
            return '';
        }

        $cacheKey = 'gtr_' . md5($target . '|' . $text);

        if ($cached = \Illuminate\Support\Facades\Cache::get($cacheKey)) {
            return $cached;
        }

        try {
            $client = new \GuzzleHttp\Client([
                'timeout'  => 6,
                'verify'   => false,
            ]);

            $response = $client->get('https://translate.googleapis.com/translate_a/single', [
                'query' => [
                    'client' => 'gtx',
                    'sl'     => 'auto',
                    'tl'     => $target,
                    'dt'     => 't',
                    'q'      => $text,
                ],
            ]);

            $body = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
            $translated = $body[0][0][0] ?? '';

            if (is_string($translated) && trim($translated) !== '') {
                \Illuminate\Support\Facades\Cache::put($cacheKey, $translated, now()->addDays(90));

                return $translated;
            }
        } catch (\Throwable $e) {
            // Translation unavailable (offline/keyed out) — fall back to source text.
        }

        return '';
    }
}

if (!function_exists('lang_url')) {
    /**
     * Returns the current URL with a ?change_language= parameter appended.
     */
    function lang_url($locale)
    {
        $url = url()->current();
        $query = [];
        parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);
        unset($query['change_language']);
        $query['change_language'] = $locale;

        return $url . '?' . http_build_query($query);
    }
}

if (!function_exists('clean_html')) {
    /**
     * Sanitizes untrusted admin-entered HTML using a tag/attribute whitelist.
     *
     * Accepts plain HTML strings, arrays (mapped recursively), or a JSON-encoded
     * object/array of locale values. Returns the same shape it received so
     * callers never have to guess.
     */
    function clean_html($value)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (is_array($value)) {
            return array_map('clean_html', $value);
        }

        $value = (string) $value;

        $decoded = parse_locale_json($value);
        if (is_array($decoded)) {
            return json_encode(
                clean_html($decoded),
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        if (stripos($value, '<') === false) {
            return $value;
        }

        $allowedTags = [
            'p', 'br', 'hr', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'ul', 'ol', 'li', 'dl', 'dt', 'dd',
            'strong', 'b', 'em', 'i', 'u', 's', 'small', 'sub', 'sup', 'mark',
            'blockquote', 'pre', 'code',
            'a', 'img',
            'span', 'div', 'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'caption',
            'figure', 'figcaption', 'details', 'summary',
        ];

        $globalAttrs = ['class', 'id', 'title', 'style'];

        $allowedAttrs = [
            'a'     => ['href', 'target', 'rel'],
            'img'   => ['src', 'alt', 'width', 'height'],
            'ol'    => ['start'],
            'table' => ['border', 'cellpadding', 'cellspacing', 'width'],
            'th'    => ['colspan', 'rowspan', 'align'],
            'td'    => ['colspan', 'rowspan', 'align'],
        ];

        $dom = new DOMDocument();
        $prev = libxml_use_internal_errors(true);
        $dom->loadHTML('<!DOCTYPE html><html><head><meta charset="utf-8"></head><body>' . $value . '</body></html>');
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $xpath = new DOMXPath($dom);

        foreach (iterator_to_array($xpath->query('//script | //style | //iframe | //object | //embed | //form | //input | //button | //textarea | //select | //option | //link | //meta | //base | //frame | //frameset | //svg | //math | //template | //video | //audio | //source | //noscript | //applet | //isindex')) as $node) {
            if ($node->parentNode) {
                $node->parentNode->removeChild($node);
            }
        }

        foreach (iterator_to_array($xpath->query('//*')) as $node) {
            $tag = strtolower($node->nodeName);
            $allowed = array_merge($globalAttrs, $allowedAttrs[$tag] ?? []);
            foreach (iterator_to_array($node->attributes) as $attr) {
                if (!in_array(strtolower($attr->nodeName), $allowed, true)) {
                    $node->removeAttribute($attr->nodeName);
                }
            }
        }

        foreach (iterator_to_array($xpath->query('//a[@href] | //img[@src] | //*[@src]')) as $node) {
            foreach (['href', 'src'] as $attrName) {
                if (!$node->hasAttribute($attrName)) {
                    continue;
                }
                if (preg_match('#^\s*(javascript|vbscript|data):#i', trim($node->getAttribute($attrName)))) {
                    $node->removeAttribute($attrName);
                }
            }
        }

        foreach (iterator_to_array($xpath->query('//*[@style]')) as $node) {
            if (preg_match('#(url\s*\(|expression\s*\(|javascript:)#i', $node->getAttribute('style'))) {
                $node->removeAttribute('style');
            }
        }

        foreach (iterator_to_array($xpath->query('//*')) as $node) {
            if (in_array(strtolower($node->nodeName), ['html', 'head', 'body', 'meta'], true)) {
                continue;
            }
            if (!in_array(strtolower($node->nodeName), $allowedTags, true)) {
                if ($node->parentNode) {
                    $node->parentNode->removeChild($node);
                }
            }
        }

        $body = $dom->getElementsByTagName('body')->item(0);
        $clean = '';
        if ($body) {
            foreach ($body->childNodes as $child) {
                $clean .= $dom->saveHTML($child);
            }
        }

        return $clean;
    }
}

if (!function_exists('safe_href')) {
    /**
     * Allows only safe hyperlink values: in-page fragments (#...) or public
     * http(s) URLs. Blocks javascript:/data:/vbscript:/file:/ftp: schemes,
     * protocol-relative URLs, and hosts that resolve to private/reserved
     * ranges (e.g. 192.168.x.x, localhost).
     */
    function safe_href($value)
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (str_starts_with($value, '#')) {
            return $value;
        }

        if (!preg_match('#^https?://#i', $value)) {
            return null;
        }

        $host = strtolower((string) parse_url($value, PHP_URL_HOST));
        $host = trim($host, '[]');

        if ($host === '') {
            return null;
        }

        if ($host === 'localhost' || str_ends_with($host, '.localhost') || str_ends_with($host, '.local')) {
            return null;
        }

        if (filter_var($host, FILTER_VALIDATE_IP) !== false) {
            if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                return null;
            }
        }

        return $value;
    }
}
