<?php
namespace HadiAghandeh\AwsMediaConvert\Converter;

use HadiAghandeh\AwsMediaConvert\Input\Input;
use HadiAghandeh\AwsMediaConvert\Output\OutputGroup;

class ConverterSetting
{
    private $outputGroups = [];
    private $inputs = [];

    public function addOutputGroup(OutputGroup $outputGroup) {
        $this->outputGroups[] = $outputGroup->toArray();

        return $this;
    }

    public function addInput(Input $input) {
        $this->inputs[] = $input->toArray();

        return $this;
    }

    public function toArray() {
        return [
            'OutputGroups' => $this->outputGroups,
            'Inputs' => $this->inputs
        ];
    }

}
