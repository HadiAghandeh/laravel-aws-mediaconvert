<?php

namespace HadiAghandeh\AwsMediaConvert;

use Aws\Credentials\Credentials;
use Aws\MediaConvert\MediaConvertClient;
use BadMethodCallException;
use Exception;
use HadiAghandeh\AwsMediaConvert\Converter\MP3;
use HadiAghandeh\AwsMediaConvert\Converter\MP4;
use HadiAghandeh\AwsMediaConvert\Converter\Thumbnail;
use HadiAghandeh\AwsMediaConvert\Converter\Wave;
use Throwable;

class AwsMediaConverter
{
	private $client;

	private $converter;
	private $wait = false;

	public function __construct()
	{
		$this->client = new MediaConvertClient([
			'version' => config('aws-mediaconvert.version'),
			'region' => config('aws-mediaconvert.region'),
			'credentials' => new Credentials(config('aws-mediaconvert.credentials.id'), config('aws-mediaconvert.credentials.secret')),
			'endpoint' => config('aws-mediaconvert.endpoint'),
		]);
	}


	public function toMP3()
	{
		$this->converter = new MP3;

		return $this;
	}

	public function toMP4()
	{
		$this->converter = new MP4;

		return $this;
	}

	public function toWave()
	{
		$this->converter = new Wave;

		return $this;
	}

	public function waitForComplete() {
		$this->wait = true;

		return $this;
	}


	public function createJob($meta = [], $tags = [])
	{
		$settings = $this->converter->getSettings();

		$iam = \config('aws-mediaconvert.iam');
		$queue = \config('aws-mediaconvert.queue');

		$job = $this->client->createJob([
			'Role' => $iam,
			'Settings' => $settings,
			'Queue' => $queue,
			'UserMetadata' => $meta ,
			'Tags' => $tags,
			'StatusUpdateInterval' => 'SECONDS_60',
			'Priority' => 0,
		]);

		$awsJob = new AwsJob($job);

		if ($this->wait) {
			$awsJob->waitForComplete();
		}

		return $awsJob;
	}

	public function createJobAsync($meta = [], $tags = [])
	{
		$settings = $this->converter->getSettings();

		$iam = \config('aws-mediaconvert.iam');
		$queue = \config('aws-mediaconvert.queue');

		$job = $this->client->createJobAsync([
			'Role' => $iam,
			'Settings' => $settings,
			'Queue' => $queue,
			'UserMetadata' => $meta ,
			'Tags' => $tags,
			'StatusUpdateInterval' => 'SECONDS_60',
			'Priority' => 0,
		]);

		return new AwsJob($job);
	}

	public function getJob(string $id) {
		return new AwsJob($this->client->getJob([
            'Id' => $id,
        ]));
	}


	public function __call($name, $arguments)
	{
		if (!$this->converter) {
			throw new BadMethodCallException("$name does not exists");
		}

		if (!method_exists($this->converter, $name)) {
			throw new BadMethodCallException("$name does not exists");
		}

		call_user_func_array(array($this->converter, $name), $arguments);

		return $this;
	}
}
