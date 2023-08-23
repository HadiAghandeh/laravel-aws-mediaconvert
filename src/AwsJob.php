<?php

namespace HadiAghandeh\AwsMediaConvert;

use Aws\Credentials\Credentials;
use Aws\MediaConvert\MediaConvertClient;

class AwsJob
{
    private $result;
    private $client;

	const STATUS_SUBMITTED = "SUBMITTED";
	const STATUS_PROGRESSING = "PROGRESSING";
	const STATUS_COMPLETE = "COMPLETE";
	const STATUS_ERROR= "ERROR";
	const STATUS_CANCELED= "CANCELED";

	public function __construct($data) {
		$this->result = $data;
	}

	public function id() {
		return $this->result->get('Job')['Id'];
	}

	public function status() {
		return $this->result->get('Job')['Status'];
	}

	public function refresh() {
		if(!$this->client) {
			$this->client = new MediaConvertClient([
				'version' => config('aws-mediaconvert.version'),
				'region' => config('aws-mediaconvert.region'),
				'credentials' => new Credentials(config('aws-mediaconvert.credentials.id'), config('aws-mediaconvert.credentials.secret')),
				'endpoint' => config('aws-mediaconvert.endpoint'),
			]);
		}

		$this->result = $this->client->getJob([
            'Id' => $this->id(),
        ]);
	}

	public function waitForComplete() {
		do {

			sleep(5);

			$this->refresh();

			if(in_array($this->status(), [self::STATUS_CANCELED, self::STATUS_ERROR, self::STATUS_COMPLETE])) {
				break;
			}

		} while (in_array($this->status(), [self::STATUS_PROGRESSING, self::STATUS_SUBMITTED]));

	}

}
