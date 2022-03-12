    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "WebSite",
            "name": "{{ dcmConfig('site_name') }}",
            "url": "{{ url('/') }}",

        }
    </script>

    {{-- "potentialAction": {
        "@type": "SearchAction",
        "target": "https://mysitelink.com/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
    } --}}