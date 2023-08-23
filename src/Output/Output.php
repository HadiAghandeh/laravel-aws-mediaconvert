<?php

declare(strict_types=1);

namespace HadiAghandeh\AwsMediaConvert\Output;

class Output
{
    private $audioDescriptions = [];
    private $configs = [];
    private $containerSettings;



    public function addAudio(AudioDescription $audio) {
        $this->audioDescriptions[] = $audio->toArray();
    }

    public function extension($value) {
        $this->configs['Extension'] = $value;

        return $this;
    }

    public function setContainerSettings($value) {
        $this->containerSettings = $value;

        return $this;
    }

    public function toArray() {
        return [
            "ContainerSettings" => $this->containerSettings,
            "AudioDescriptions" => $this->audioDescriptions,
            ...$this->configs
        ];
    }
}
