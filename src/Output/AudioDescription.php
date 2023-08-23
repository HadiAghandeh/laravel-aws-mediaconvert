<?php

declare(strict_types=1);

namespace HadiAghandeh\AwsMediaConvert\Output;

class AudioDescription
{
    private $codecSettings;
    private $source;

    public function setSource(int $number) {
        $this->source = "Audio Selector $number";

        return $this;
    }

    public function setCondecSettings($value) {
        $this->codecSettings = $value;

        return $this;
    }

    public function toArray() {
        return [
            'AudioSourceName' => $this->source,
            'CodecSettings' => $this->codecSettings
        ];
    }
}
