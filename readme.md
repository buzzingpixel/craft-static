# Craft Static

## Static file caching for Craft 3

## Installing

(Please see section below for instructions on setting up the cron job to clear cache for future or expiring entries)

### Composer

From the command line run:

`composer require buzzingpixel/craft-static`

After installing via composer, go to your Craft CP > Settings > Plugins and install Craft Static.

### Craft  Plugin Store

The other way is through the plugin store in the Craft CP.

## Configuration

There are two globally configurable options:

- `cachePath` - defaults to `$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'cache'`
- `nixBasedClearCache` - defaults to `true`. Leave set to true on Unix based systems because it clears the cache by executing `rm -rf` which is much faster. Otherwise, PHP has to iterate through each file and directory in the cache path and delete them individually, which can take some time.

## Usage

### Usage in Twig

The entire contents of your HTML output should be wrapped in the `{% static %}` tag so it's best to put this in the outermost layout file. You can also specify whether the tag should actually cache or not so you can disable caching in some scenarios and still wrap your entire contents in the tag.

Example:

```twig
{% set shouldCache = true %}
{% static cache shouldCache %}
    {% minify %}
        <html>
        <head>
            <title>Test title</title>
        </head>
        <body>
            <div>Test body</div>
        </body>
        </html>
    {% endminify %}
{% endstatic %}
```

### Alternative usage in a controller

If you would like to use a controller to render your output, or otherwise statically cache from PHP, you can use:

```php
\buzzingpixel\craftstatic\Craftstatic::$plugin->getStaticHandler()->handleContent();
```

A full(er) example:

```php
<?php

namespace some\module\namespace;

use yii\web\Response;
use craft\web\Controller;
use buzzingpixel\craftstatic\Craftstatic;

class MyController extends Controller
{
    protected $allowAnonymous = true;
    
    public function actionSomeAction(): Response
    {
        // ... do stuff

        $response = $this->renderTemplate($template, $vars);
        
        // Potentially check an environment variable so you don't cache
        // in dev environment
        if (getenv('STATIC_CACHE_ENABLED') === 'true') {
            Craftstatic::$plugin->getStaticHandler()->handleContent(
                $response->data
            );
        }

        return $response;
    }
}
```

### Cache clearing

The cache will be purged any time an element is saved. It can also be cleared manually from the CP's Utilites > Clear Caches section.

### Cache Clearing via command line

The cache can be cleared from the command line, but note that you must define the `cachePath` via custom config because `$_SERVER['DOCUMENT_ROOT']` is not defined when running on the console and you might end up deleting things on the server you didn't mean to. Create a file craft-static.php and put it in your config directory. You can use a config setting something like this:

```php
<?php
return [
    'cachePath' => realpath(dirname(__DIR__)) . '/web/cache',
];
```

To purge static cache from the command line, run: `./craft craft-static/cache/purge`

### Cron job

Craft Static keeps track of future and expiring entries but in order for Craft Static to clear the static cache when an entry expires or it's previously future post date because past, you need to be running a cron job every minute. That command is:

```bash
./craft craft-static/cache/check-tracking
```
 
Here's an example of what that cron job might look like:

```bash
* * * * * /user/bin/php /path/to/projet/craft craft-static/cache/check-tracking >> /dev/null 2>&1
```

## License

Copyright 2019 BuzzingPixel, LLC

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
