<?php if (!empty(config('app.ads_yllix')['pub_id'])): ?>
    <script type="text/javascript" src="https://sailif.com/bnr.php?section=General&pub={{ config('app.ads_yllix')['pub_id'] }}&format={{ config('app.ads_yllix')['format'] }}&ga=a&bg=1"></script>
    <noscript>
        <a href="https://yllix.com/publishers/{{ config('app.ads_yllix')['pub_id'] }}" target="_blank">
            <img src="//ylx-aff.advertica-cdn.com/pub/{{ config('app.ads_yllix')['format'] }}" style="border:none;margin:0;padding:0;vertical-align:baseline;" alt="ylliX - Online Advertising Network" />
        </a>
    </noscript>
<?php endif; ?>