# Texturize #

[a]: https://www.php.net/manual/en/book.dom.php

Translates -- "texturizes" -- ASCII punctuation characters into typographically-correct punctuation. Based loosely off of an ancient Wordpress method used for the same purpose.

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


    public static function withString(string $data): string;
    public static function withDOM(\DOMDocument|\DOMElement &$node): \DOMDocument|\DOMElement;
}
```

#### Properties ####

* *curlyQuotes* (bool): If true, texturizes curly quotes ("" to “”).
* *dashes* (bool): If true, texturizes dashes (-- to –).
* *ellipses* (bool): If true, texturizes ellipses (... to …).

#### dW\Texturize::withString ####

"Texturizes" a given string.

```php
public static function withString(string $data): string;
```

* `data`: The string to "texturize"

#### dW\Texturize::withDOM ####

```php
public static function withDOM(\DOMDocument|\DOMElement &$node): \DOMDocument|\DOMElement;
```

* `node`: The `\DOMDocument` or `\DOMElement` to texturize.
