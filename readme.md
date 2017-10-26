# Craft Static
## Static file caching for Craft 3

## Installing

`composer require buzzingpixel/craft-static`

## Configuration

There are two globally configurable options:

- `cachePath` - defaults to `$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'cache'`
- `nixBasedClearCache` - defaults to `true`. Leave set to true on Unix based systems because it clears the cache by executing `rm -rf` which is much faster. Otherwise, PHP has to iterate through each file and directory in the cache path and delete them individually, which can take some times.

## Usage

The entire contents of your HTML out put should be wrapped in the `{% static %}` tag so it's best to put this in the outermost layout file. You can also specify whether the tag should actually cache or not so you can disable caching in some scenarios and still wrap your entire contents in the tag.

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

### Cache clearing

The cache will be purged any time an element is saved. It can also be cleared manually from the CP's Utilites > Clear Caches section.

### Cache Clearing via command line

The cache can be cleared from the command line, but note that you must define the `cachePath` via custom config because `$_SERVER['DOCUMENT_ROOT']` is not defined when running on the console and you might end up deleting things on the server you didn't mean to. You can use a config setting something like this:

```php
<?php
return [
    'cachePath' => realpath(dirname(__DIR__)) . '/web/cache',
];
```

To purge static cache from the command line, run: `./craft craft-static/cache/purge`

## License

Copyright 2017 BuzzingPixel, LLC

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0).

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
