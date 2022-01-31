# Texturize #

[a]: https://www.php.net/manual/en/book.dom.php

Translates -- "texturizes" -- ASCII punctuation characters into typographically-correct punctuation. Based loosely off of an ancient Wordpress method used for the same purpose. The major difference with this library is that this library can translate strings in complex markup and within attributes.

## Warning Before Using ##

I have used this code in various forms for more than 10 years, but I am releasing it initially as "beta" software because there are no tests. Writing tests for this will be superficial, but I have to have time to do it.

## Requirements ##

* PHP 8.0.2 or newer with the following extensions:
  - [dom][a] extension
* Composer 2.0 or newer

## Documentation ##

Texturize is a single static class.

### dW\Texturize ###

```php
class dW\Texturize {
    // Flags for toggling features
    public static bool $curlyQuotes = true;
    public static bool $dashes = true;
    public static bool $ellipses = true;

    // Text nodes which are descendants of these elements won't be selected for
    // texturization
    public static array $ignoredAncestors = [ 'code', 'command', 'kbd', 'keygen', 'pre', 'samp', 'script', 'style', 'tt', 'var' ];

    // Attributes which should be texturized
    public static array $includedAttributes = [ '@alt', '@aria-label', 'meta[not(@name="viewport")]/@content', '@longdesc', '@rel', '@rev', '@title' ];


    public static function withString(string $data): string;
    public static function withDOM(\DOMDocument|\DOMElement &$node): \DOMDocument|\DOMElement;
}
```

#### Properties ####

* *curlyQuotes* (bool): If true, texturizes curly quotes (eg: "" to “”).
* *dashes* (bool): If true, texturizes dashes (eg: -- to –).
* *ellipses* (bool): If true, texturizes ellipses (eg: ... to …).
* *ignoredAncestors* (array): An array of element names whose text node descendants will be ignored.
* *includedAttributes* (array): An array of XPath snippets corresponding to attributes that should be texturized.

#### dW\Texturize::withString ####

"Texturizes" a given string.

```php
public static function withString(string $data): string;
```

* `data`: The string to "texturize"

#### dW\Texturize::withDOM ####

"Texturizes" a given `\DOMDocument` or `\DOMElement`. It is aware of adjacent elements and will successfully translate complex DOM structures.

```php
public static function withDOM(\DOMDocument|\DOMElement &$node): \DOMDocument|\DOMElement;
```

* `node`: The `\DOMDocument` or `\DOMElement` to texturize.
