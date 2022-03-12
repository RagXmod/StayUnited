    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "Corporation",
            "name": "{{ dcmConfig('site_name') }}",
            "url": "{{ url('/') }}",
            "logo": "{{ asset(dcmConfig('site_logo')) }}",
            "sameAs": [
                "{{ dcmConfig('social_facebook') }}",
                "{{ dcmConfig('social_twitter') }}",
                "{{ dcmConfig('social_google_plus') }}",
                "{{ dcmConfig('social_pinterest') }}"
            ]
        }
    </script>
