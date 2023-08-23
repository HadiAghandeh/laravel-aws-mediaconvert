<?php

namespace HadiAghandeh\AwsMediaConvert\Converter;

use HadiAghandeh\AwsMediaConvert\Input\AudioSelector;
use HadiAghandeh\AwsMediaConvert\Input\Input;
use HadiAghandeh\AwsMediaConvert\Input\VideoSelector;
use HadiAghandeh\AwsMediaConvert\Output\AudioDescription;
use HadiAghandeh\AwsMediaConvert\Output\Output;
use HadiAghandeh\AwsMediaConvert\Output\OutputGroup;

class MP4 extends Converter
{
    public function __construct()
    {
        parent::__construct();
    }
	public $type = 'MP4';

	private $thumbnailEnabled = false;
	private $framerateNumerator = 1;
	private $framerateDenominator = 1;
	private $maxCaptures = 1;
	private $quality = 80;
	private $thumbanilWidth = 500;

	public function withThumbnail($value = true) {
		$this->thumbnailEnabled = $value;

		return $this;
	}

	private function getThumbnailOutputGroup() {
		return [
			'CustomName' => 'Thumbnails',
			'Name' => 'File Group',
			'Outputs' => [
				[
					'ContainerSettings' => [
						'Container' => 'RAW',
					],
					'VideoDescription' => [
						'ScalingBehavior' => 'DEFAULT',
						'TimecodeInsertion' => 'DISABLED',
						'AntiAlias' => 'ENABLED',
						'Sharpness' => 50,
						'CodecSettings' => [
							'Codec' => 'FRAME_CAPTURE',
							'FrameCaptureSettings' => [
								'FramerateNumerator' => $this->framerateNumerator, // to be set dynamically
								'FramerateDenominator' => $this->framerateDenominator, // to be set dynamically
								'MaxCaptures' => $this->maxCaptures, // to be set dynamically
								'Quality' => $this->quality, // to be set dynamically
							],
						],
						'AfdSignaling' => 'NONE',
						'DropFrameTimecode' => 'ENABLED',
						'RespondToAfd' => 'NONE',
						'ColorMetadata' => 'INSERT',
						'Width' => $this->thumbanilWidth, // to be set dynamically
					],
				],
			],
			'OutputGroupSettings' => [
				'Type' => 'FILE_GROUP_SETTINGS',
				'FileGroupSettings' => [
					'Destination' => $this->dirdestination . '-thumb', // to be set dynamically
					'DestinationSettings' => [
						'S3Settings' => [
							'AccessControl' => [
								'CannedAcl' => 'PUBLIC_READ',
							],
						],
					],
				],
			],
		];
	}

	private function getMP4OutputGroup() {
		return [
				'CustomName' => 'MP4',
				'Name' => 'File Group',
				'Outputs' => [
					[
						'ContainerSettings' => [
							'Container' => 'MP4',
							'Mp4Settings' => [
								'CslgAtom' => 'INCLUDE',
								'FreeSpaceBox' => 'EXCLUDE',
								'MoovPlacement' => 'PROGRESSIVE_DOWNLOAD',
							],
						],
						'VideoDescription' => [
							'ScalingBehavior' => 'DEFAULT',
							'TimecodeInsertion' => 'DISABLED',
							'AntiAlias' => 'ENABLED',
							'Sharpness' => 50,
							'CodecSettings' => [
								'Codec' => 'H_264',
								'H264Settings' => [
									'InterlaceMode' => 'PROGRESSIVE',
									'NumberReferenceFrames' => 3,
									'Syntax' => 'DEFAULT',
									'Softness' => 0,
									'GopClosedCadence' => 1,
									'GopSize' => 90,
									'Slices' => 1,
									'GopBReference' => 'DISABLED',
									'MaxBitrate' => 8000000,
									'SlowPal' => 'DISABLED',
									'SpatialAdaptiveQuantization' => 'ENABLED',
									'TemporalAdaptiveQuantization' => 'ENABLED',
									'FlickerAdaptiveQuantization' => 'DISABLED',
									'EntropyEncoding' => 'CABAC',
									'FramerateControl' => 'INITIALIZE_FROM_SOURCE',
									'RateControlMode' => 'QVBR',
									'QvbrSettings' => [
										'QvbrQualityLevel' => 7,
										'QvbrQualityLevelFineTune' => 0,
									],
									'CodecProfile' => 'MAIN',
									'Telecine' => 'NONE',
									'MinIInterval' => 0,
									'AdaptiveQuantization' => 'HIGH',
									'CodecLevel' => 'AUTO',
									'FieldEncoding' => 'PAFF',
									'SceneChangeDetect' => 'ENABLED',
									'QualityTuningLevel' => 'SINGLE_PASS',
									'FramerateConversionAlgorithm' => 'DUPLICATE_DROP',
									'UnregisteredSeiTimecode' => 'DISABLED',
									'GopSizeUnits' => 'FRAMES',
									'ParControl' => 'INITIALIZE_FROM_SOURCE',
									'NumberBFramesBetweenReferenceFrames' => 2,
									'RepeatPps' => 'DISABLED',
								],
							],
							'AfdSignaling' => 'NONE',
							'DropFrameTimecode' => 'ENABLED',
							'RespondToAfd' => 'NONE',
							'ColorMetadata' => 'INSERT',
						],
						'AudioDescriptions' => [
							[
								'AudioTypeControl' => 'FOLLOW_INPUT',
								'CodecSettings' => [
									'Codec' => 'AAC',
									'AacSettings' => [
										'AudioDescriptionBroadcasterMix' => 'NORMAL',
										'Bitrate' => 96000,
										'RateControlMode' => 'CBR',
										'CodecProfile' => 'LC',
										'CodingMode' => 'CODING_MODE_2_0',
										'RawFormat' => 'NONE',
										'SampleRate' => 48000,
										'Specification' => 'MPEG4',
									],
								],
								'LanguageCodeControl' => 'FOLLOW_INPUT',
							],
						],
					],
				],
				'OutputGroupSettings' => [
					'Type' => 'FILE_GROUP_SETTINGS',
					'FileGroupSettings' => [
						'Destination' => $this->dirdestination,
						'DestinationSettings' => [
							'S3Settings' => [
								'AccessControl' => [
									'CannedAcl' => 'PUBLIC_READ',
								],
							],
						],
					],
				],
		];
	}

	private function getOutputGroups() {
		if($this->thumbnailEnabled) {
			return [
				$this->getThumbnailOutputGroup(),
				$this->getMP4OutputGroup()
			];
		} else {
			return [
				$this->getMP4OutputGroup()
			];
		}

	}

    public function getSettings()
    {
        return [
            'OutputGroups' => $this->getOutputGroups(),
            'AdAvailOffset' => 0,
            'Inputs' => [
                [
                    'AudioSelectors' => [
                        'Audio Selector 1' => [
                            'Offset' => 0,
                            'DefaultSelection' => 'DEFAULT',
                            'ProgramSelection' => 1,
                        ],
                    ],
                    'VideoSelector' => [
                        'ColorSpace' => 'FOLLOW',
                    ],
                    'FilterEnable' => 'AUTO',
                    'PsiControl' => 'USE_PSI',
                    'FilterStrength' => 0,
                    'DeblockFilter' => 'DISABLED',
                    'DenoiseFilter' => 'DISABLED',
                    'TimecodeSource' => 'EMBEDDED',
                    'FileInput' => $this->fileSource
                ],
            ],
        ];
    }
}
