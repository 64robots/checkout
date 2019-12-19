<?php
namespace R64\Checkout\Helpers;

class HashTagExtractor
{
    private $text;
    private $processed = null;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function tags() : array
    {
        return $this->getProcessed();
    }

    private function getProcessed() : array
    {
        if (is_null($this->processed)) {
            preg_match_all($this->getMatcherRules(), $this->text, $matches);

            $this->processed = $matches[0];
        }

        return $this->processed;
    }

    private function getMatcherRules()
    {
        return "/\#[a-zA-Z]+/";
    }
}
