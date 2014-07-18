<?php

namespace PrettyXml;

class Formatter
{
    /**
     * @var int
     */
    private $depth;
    /**
     * @var int
     */
    private $indent = 4;
    /**
     * @varstring
     */
    private $padChar = ' ';

    /**
     * @param string $xml
     * @return string
     */
    public function format($xml)
    {
        $output = '';
        $this->depth = 0;

        $parts = $this->getXmlParts($xml);

        if (strpos($parts[0], '<?xml') === 0) {
            $output = array_shift($parts) . PHP_EOL;
        }

        foreach($parts as $part) {
            if ($this->isClosingTag($part)) {
                $this->depth--;
            }
            $output .= $this->getPaddedString($part) . PHP_EOL;
            if ($this->isOpeningTag($part)) {
                $this->depth++;
            }
        }

        return trim($output);
    }

    private function getXmlParts($xml)
    {
        $withNewLines = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", trim($xml));
        return explode("\n", $withNewLines);
    }

    private function getPaddedString($part)
    {
        return str_pad($part, strlen($part) + ($this->depth * $this->indent), $this->padChar, STR_PAD_LEFT);
    }

    private function isOpeningTag($part)
    {
        return preg_match('/^<[^\/]\w*>$/', $part);
    }

    private function isClosingTag($part)
    {
        return preg_match('/^<\//', $part);
    }
}
