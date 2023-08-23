<?php

use App\Jobs\ConvertSoundAnswerToWav;
use App\Jobs\RateByElsa;
use App\Media;
use App\QuestionUserAnswer;
use \Aws\Credentials\Credentials;
use \Aws\MediaConvert\MediaConvertClient;
use HadiAghandeh\AwsMediaConvert\AwsJob;
use HadiAghandeh\AwsMediaConvert\AwsMediaConverter;
use HadiAghandeh\AwsMediaConvert\Http\Controllers\WebhookController;

Route::get('test2', function() {
	$m = Media::find(171);

	$m->toMP4($m->getDiskPath(), $m->directory . "/");
});


Route::post('aws-mediaconvert/webhook/job-event', WebhookController::class);
Route::get('aws-mediaconvert/webhook/job-event', WebhookController::class);
