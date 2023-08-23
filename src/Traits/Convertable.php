<?php

namespace HadiAghandeh\AwsMediaConvert\Traits;

use HadiAghandeh\AwsMediaConvert\AwsMediaConverter;
use HadiAghandeh\AwsMediaConvert\Models\AwsMediaConversion;

trait Convertable
{
	/**
     * Get all of the media items' conversions.
     */
    public function conversions()
    {
        return $this->morphMany(AwsMediaConversion::class, 'model');
    }

	public function toMP3($source, $destination) {
		$c = new AwsMediaConverter();

		$job = $c->toMP3()
		->source($source)
		->destination($destination)
		->createJob([
			"_aws_mediaconvert_type" => 'MP3'
		]);

		$this->conversions()->create(['job_id' => $job->id()]);
	}

	public function toMP4($source, $destination, $withThumbnail = true) {
		$c = new AwsMediaConverter();

		$job = $c->toMP4()
		->source($source)
		->withThumbnail($withThumbnail)
		->destination($destination)
		->createJob([
			"_aws_mediaconvert_type" => 'MP4',
			"_aws_mediaconvert_withThumbnail" => $withThumbnail * 1, // AWS Does not accept none string meta
		]);

		$this->conversions()->create(['job_id' => $job->id()]);
	}

	public function toWave($source, $destination, $meta = []) {
		$c = new AwsMediaConverter();

		$job = $c->toWave()
		->source($source)
		->destination($destination)
		->createJob([
			"_aws_mediaconvert_type" => "WAV"
		]);

		$this->conversions()->create(['job_id' => $job->id()]);
	}

	abstract public function onConversionComplete($message);
}
