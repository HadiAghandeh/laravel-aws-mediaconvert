<?php
namespace HadiAghandeh\AwsMediaConvert\Input;

class AudioSelector
{
    private $selector = [];

    public function __construct($selector = []) {
        $this->selector = $selector;
    }

    public function toArray() {
        return $this->selector;
    }
}
