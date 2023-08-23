<?php

namespace HadiAghandeh\AwsMediaConvert\Converter;

use HadiAghandeh\AwsMediaConvert\Input\AudioSelector;
use HadiAghandeh\AwsMediaConvert\Input\Input;
use HadiAghandeh\AwsMediaConvert\Output\AudioDescription;
use HadiAghandeh\AwsMediaConvert\Output\Output;
use HadiAghandeh\AwsMediaConvert\Output\OutputGroup;

class MP3 extends Converter
{
	public function __construct()
	{
		parent::__construct();
	}

	public $type = 'MP3';

	private $bitrate = 96000;
	private $samplerate = 48000;
	private $rateControlMode = "CBR";

	public function getSettings()
	{
		$input = new Input();
		$input->setInput($this->fileSource);
		$input->timecodeSource(Input::TIMECODE_SOURCE_ZEROBASED);

		$audioSelector = new AudioSelector([
			"DefaultSelection" => "DEFAULT"
		]);

		$input->addAudioSelectors($audioSelector);

		$outputgroup = new OutputGroup();

		$outputgroup->setAsFileGroup();

		$outputgroup->setOutputGroupSettings([
			"Type" => "FILE_GROUP_SETTINGS",
			"FileGroupSettings" => [
				"Destination" => $this->dirdestination
			]
		]);

		$output = new Output();
		// $output->extension('mp3');
		$output->setContainerSettings([
			"Container" => "RAW"
		]);

		$audioDescription = new AudioDescription();

		$audioDescription->setSource(1)->setCondecSettings([
			"Codec" => "MP3",
			"Mp3Settings" => [
				"Bitrate" => $this->bitrate,
				"RateControlMode" => $this->rateControlMode,
				"SampleRate" => $this->samplerate
			]
		]);

		$output->addAudio($audioDescription);
		$outputgroup->addOutput($output);

		$this->settings = new ConverterSetting();
		$this->settings->addOutputGroup($outputgroup);
		$this->settings->addInput($input);

		return $this->settings->toArray();
	}
}
