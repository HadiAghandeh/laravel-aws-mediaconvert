<?php

declare(strict_types=1);

namespace HadiAghandeh\AwsMediaConvert\Output;

class OutputGroup
{
    private $name;
    private $settings;
    private $outputs = [];

    const NAME_FILE_GROUP = "File Group";

    public function setAsFileGroup() {
        $this->name = self::NAME_FILE_GROUP;
    }

    public function setOutputGroupSettings($settings) {
        $this->settings = $settings;
    }

    public function addOutput(Output $o) {
        $this->outputs[] = $o->toArray();
    }

    public function toArray() {
        return [
            'Name' => $this->name,
            'Outputs' => $this->outputs,
            'OutputGroupSettings' => $this->settings
        ];
    }
}
