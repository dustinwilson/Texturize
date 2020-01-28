<?php
declare(strict_types=1);
namespace dW;

class Texturize {
    // Flags for toggling features
    public static $curlyQuotes = true;
    public static $dashes = true;
    public static $ellipses = true;

    // Text nodes which are descendants of these elements won't be selected for
    // texturization
    protected static $ignoredAncestors = [ 'code', 'command', 'kbd', 'keygen', 'pre', 'samp', 'script', 'style', 'tt', 'var' ];

    // Attributes which should be texturized
    protected static $includedAttributes = [ '@alt', '@aria-label', 'meta[not(@name="viewport")]/@content', '@longdesc', '@rel', '@rev', '@title' ];

    // Regexes used for texturization of curly quotes
    protected static $curlyQuoteRegexes = [
        [ '/(\0)?\'(\0)?(bout|cause|nuff|round|tain\'t|til|tis|twas|twere|twill|em)/S',
          '/(\0)?\'(\0)?([\0\d]+(?:\')?\0?s)/S',
          '/^(\0)?\'(\0)?(?=[[:punct:]]\0?\B)/S',
          '/(\0?(?:\s|\x{200B}|\A|\")\0?)\'/Su',
          '/(\0?[^ \t\r\n\[\{\(\-]\0?)?\'(\0)?(?(1)|(?=\s|\x{200B}|s\b|\Z))/Siu',
          '/(\0?(?:\x{200B}|\s|\A)\0?)"(\0)?(?=[^\s\x{200B}])/Su',
          '/(\0?[^ \t\r\n\[\{\(\-]\0?)?\"(\0)?(?(1)|(?=\s|\x{200B}|\Z))/Siu' ],
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
          '/(?<!xn)(?<!-)(\0)?---(\0)?(?!-)/S' ],
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
    
    public static function withDOM(\DOMDocument &$data): \DOMDocument {
        // Select all text nodes in the document which aren't descendants of the
        // 'ignored' elements defined above.
        $xpath = new \DOMXPath($data);
        $query = '//text()';
        foreach (self::$ignoredAncestors as $i) {
            $query .= "[not(ancestor::$i)]";
        }

        $nodes[0] = $xpath->query($query);

        // Select all attributes in the document which are the 'included'
        // attributes defined above.
        $query = '';
        foreach (self::$includedAttributes as $i) {
            $query .= "//$i | ";
        }
        $query = rtrim($query, ' |');

        $nodes[1] = $xpath->query($query);

        foreach ($nodes as $i => $n) {
            // Iterate through the DOMNodeList and implode it into a string
            // separated by the NULL character. This works because the NULL
            // character isn't allowed in HTML documents and therefore will never be
            // encountered. The regular expressions above are NULL character aware,
            // and this allows for curly quotes to be aware of what is in adjacent
            // text nodes.
            $imploded = '';
            foreach ($n as $n2) {
                $imploded .= (($i === 0) ? $n2->data : $n2->value) . chr(0);
            }

            $imploded = rtrim($imploded, chr(0));
            $imploded2 = self::withString($imploded);
            if ($imploded === $imploded2) {
                return $data;
            }

            $exploded = explode(chr(0), $imploded2);

            for ($j = 0; $j < count($exploded); $j++) {
                $e = $exploded[$j];
                $x = $n->item($j);

                if ($e !== (($i === 0) ? $x->data : $x->value)) {
                    $x->data = $e;
                }
            }
        }

        // Return the DOMDocument out of convenience, but it is edited in place
        // anyway.
        return $data;
    }
}