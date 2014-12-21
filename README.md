Sitemap Cache Warmer
====================

This PHP script crawls URL:s based on a sitemap. It is used to keep your cache warm by visiting all the pages in your sitemap 
at regular intervals. It supports sub-sitemap (Sitemap index).

#### Usage

Edit the file config.php and change the key parameter to a secret value. Upload the file onto your web host, preferably into
its own folder (for example, /warm-cache)

Once you have uploaded this file onto your web host, you can visit the following URL to traverse a sitemap and visit all its URL:s:

```
http://example.com/warm-cache/warm.php?key=SECRET_KEY&url=http://example.com/sitemap.xml&sleep=0
```

###### Available parameters

**key** - Secret key, as entered in config.php (Required)  
**url** - URL to the root sitemap, usually /sitemap.xml (Required)  
**sleep** - Amount of time to sleep between each request in seconds. Used for throttling on slow hosts. (Optional, default is to not throttle.)  

#### Scheduling the crawl

You will need to use CRON to schedule the crawls as often as you wish. Here's an example using cURL and crontab:

```
0 * * * * curl http://example.com/warm-cache/warm.php?key=SECRET_KEY&url=http://example.com/sitemap.xml&sleep=0 >/dev/null 2>&1
```

If your host provides a CRON URL visiting function, all you need to do is enter the URL, as described in the "Usage" section.

#### Output

The script will provide a JSON output with stats about the crawl, example:

```json
{
    "status": "OK",
    "message": "Processed sitemap: http://example.com/sitemap.xml",
    "duration": 9.5575199127197,
    "count" : 4
    "log": [
        "Processed sub-sitemap: http://example.com/post-sitemap.xml",
        "Processed sub-sitemap: http://example.com/page-sitemap.xml",
    ],
    "visited_urls": [
        "http://example.com/page1/",
        "http://example.com/page2/",
        "http://example.com/page3/",
        "http://example.com/page4/",
    ]
}
```

#### Crawl strategies

If you employ time-based static page cache, you can schedule your crawls to coincide with half the cache expiration time.

For example, if your expiration time is one hour (3600 seconds), you can schedule the crawls to take place every thirty minutes (1800 seconds).

If you have a lot of pages and few visitors, this may cause increased load on the server. For low-traffic deployments, use a long cache expiration time (24 hours or more) and invalidate cache when page content changes.

#### Requirements

* SimpleXML
* allow_url_fopen in php.ini (Enabled on most hosts)

#### Compatibility

The plugin has been tested with the WordPress plugins [Yoast WordPress SEO](https://wordpress.org/plugins/wordpress-seo/) and [Google XML Sitemaps](https://wordpress.org/plugins/google-sitemap-generator/). It should work with any sitemap which conforms to the [sitemap standard](http://www.sitemaps.org/protocol.html).
