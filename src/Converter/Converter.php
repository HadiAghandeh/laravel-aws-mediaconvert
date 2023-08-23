<?php
namespace HadiAghandeh\AwsMediaConvert\Converter;

class Converter
{
	protected ConverterSetting $settings;

	public function __construct()
    {
    }

	public function getSettings() {
		return $this->settings->toArray();
	}

	protected $fileSource;
	protected $dirdestination;

	public function source($path, $bucket = null)
	{
		if (!$bucket) {
			$bucket = config('aws-mediaconvert.source_bucket');
		}

		$this->fileSource = "s3://$bucket/$path";

		return $this;
	}


	public function destination($path, $bucket = null)
	{
		if (!$bucket) {
			$bucket = config('aws-mediaconvert.dest_bucket');
		}

		$this->dirdestination = "s3://$bucket/$path";

		return $this;
	}
}
