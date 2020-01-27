<?php
declare(strict_types=1);
namespace dW;

class Texturize {
    // Flags for toggling features
    public static $curlyQuotes = true;
    public static $dashes = true;
    public static $ellipses = true;


    // Block elements used for texturization. When texturizing these block elements
    // which do not contain block elements will be grabbed and their text nodes will be
    // texturized.
    protected static $blockElements = ['article','aside','blockquote','dd','dd','dir',
                                       'div','dl','dt','fieldset','figcaption','figure',
                                       'footer','form','frame','frameseet','h1','h2','h3',
                                       'h4','h5','h6','header','hgroup','isindex','li',
                                       'main','menu','nav','noframes','noscript','ol','p',
                                       'pre','section','table','tbody','td','tfoot','th',
                                       'thead','tr','ul'];

    // Code elements used for texturization
    protected static $codeElements = ['code','command','kbd','keygen','samp','tt','var'];

    // Header elements used for texturization
    protected static $headerElements = ['h1','h2','h3','h4','h5','h6'];

    // Regexes used for texturization of curly quotes
    protected static $curlyQuoteRegexes = [
        [ '/(\0)?\'(\0)?(bout|cause|nuff|round|tain\'t|til|tis|twas|twere|twill|em)/S',
          '/(\0)?\'(\0)?([\0\d]+(?:\')?\0?s)/S',
          '/^(\0)?\'(\0)?(?=[[:punct:]]\0?\B)/S',
          '/(\0?(?:\s|\x{200B}|\A|\")\0?)\'/Su',
          '/(\0?[^ \t\r\n\[\{\(\-]\0?)?\'(\0)?(?(1)|(?=\s|\x{200B}|s\b|\Z))/Siu',
          '/(\0?(?:\x{200B}|\s|\A)\0?)"(\0)?(?=[^\s\x{200B}])/Su',
          '/(\0?[^ \t\r\n\[\{\(\-]\0?)?\"(\0)?(?(1)|(?=\s|\x{200B}|\Z))/Siu'],
        [ '$1’$2$3',
          '$1’$2$3',
          '$1’',
          '$1‘',
          '$1’$2',
          '$1“$2',
          '$1”$2' ]
    ];

    // Regexes used for texturization of dashes
    protected static $dashRegexes = [
        [ '/(?<!xn)(?<!-)(\0)?--(\0)?(?!-)/S',
          '/(?<!xn)(?<!-)(\0)?---(\0)?(?!-)/S'],
        [ '$1—$2',
          '$1—$2' ]
    ];

    // Regexes used for texturization of ellipses
    protected static $ellipsisRegexes = [
        [ '/(\0)?(?:\.\.\.|\. \. \.)(\0)?/S' ],
        [ '$1…$2' ]
    ];


    public static function withString(string $data): string {
        if (self::$curlyQuotes) {
            $data = preg_replace(self::$curlyQuoteRegexes[0], self::$curlyQuoteRegexes[1], $data);
        }

        if (self::$dashes) {
            $data = preg_replace(self::$dashRegexes[0], self::$dashRegexes[1], $data);
        }

        if (self::$ellipses) {
            $data = preg_replace(self::$ellipsisRegexes[0], self::$ellipsisRegexes[1], $data);
        }

        return $data;
    }

    public static function withDOM(\DOMDocument $data): \DOMDocument {
        return $data;
    }
}