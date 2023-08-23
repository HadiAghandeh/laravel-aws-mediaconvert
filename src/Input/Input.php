<?php
namespace HadiAghandeh\AwsMediaConvert\Input;

class Input
{
    private $fileinput;
    private $audioSelectors = [];
    private $videoSelector;
    private $configs = [];

    const TIMECODE_SOURCE_EMBEDED = 'EMBEDDED';
    const TIMECODE_SOURCE_ZEROBASED= 'ZEROBASED';

    public function setInput($path) {
        $this->fileinput = $path;
    }

    public function addAudioSelectors(AudioSelector $audio) {
        $n = count($this->audioSelectors) + 1;

        $this->audioSelectors["Audio Selector $n"] = $audio->toArray();

        return $this;
    }

	public function setVideoSelector(VideoSelector $video) {
		$this->videoSelector = $video->toArray();
	}

    public function timecodeSource($value = self::TIMECODE_SOURCE_EMBEDED) {
        $this->configs["TimecodeSource"] = $value;

        return $this;
    }

	public function setSettings($settings) {
		$this->configs = array_merge($this->configs, $settings);

		return $this;
	}

    public function toArray() {
        return array_merge([
            "AudioSelectors" => $this->audioSelectors,
            "FileInput" => $this->fileinput
        ], $this->configs);
    }
}
