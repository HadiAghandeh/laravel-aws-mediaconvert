<?php

namespace HadiAghandeh\AwsMediaConvert\Converter;

class Wave extends Converter
{
	public function __construct()
	{
		parent::__construct();
	}

	public $type = 'WAV';

	public function getSettings()
	{
		return [
			"TimecodeConfig" => [
				"Source" => "ZEROBASED"
			],
			"OutputGroups" => [
				[
					"Name" => "File Group",
					"Outputs" => [
						[
							"ContainerSettings" => [
								"Container" => "RAW"
							],
							"AudioDescriptions" => [
								[
									"AudioSourceName" => "Audio Selector 1",
									"CodecSettings" => [
										"Codec" => "WAV",
										"WavSettings" => []
									]
								]
							]
						]
					],
					"OutputGroupSettings" => [
						"Type" => "FILE_GROUP_SETTINGS",
						"FileGroupSettings" => [
							"Destination" => $this->dirdestination
						]
					]
				]
			],
			"Inputs" => [
				[
					"AudioSelectors" => [
						"Audio Selector 1" => [
							"DefaultSelection" => "DEFAULT"
						]
					],
					"TimecodeSource" => "ZEROBASED",
					"FileInput" => $this->fileSource
				]
			]
		];
	}
}
