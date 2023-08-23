<?php

namespace HadiAghandeh\AwsMediaConvert\Http\Controllers;

use Illuminate\Routing\Controller;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use Exception;
use HadiAghandeh\AwsMediaConvert\AwsMediaConverter;
use HadiAghandeh\AwsMediaConvert\Models\AwsMediaConversion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as FacadesLog;
use Throwable;
use Log;
class WebhookController extends Controller
{

	public function __invoke()
	{
		try {
			$message = $this->getMessage();

			// Log::info('incoming MediaConvert webhook message', $message);

			if ($message && !array_key_exists('detail', $message)) {
				// Log::alert('incoming MediaConvert webhook: "detail"-key does not exist');

				return;
			}
			// dd(2);

			$detail = $message['detail'];

			if (!array_key_exists('status', $detail)) {
				// Log::alert('incoming MediaConvert webhook: "status"-key does not exist');

				return;
			}

			$status = $detail['status'];

			$this->updateJobStatus($message);

		} catch (Throwable $t) {
			// FacadesLog::info('aws-mediaconvert', \request()->all());
		}
	}

	private function updateJobStatus($message){
		$status = $message['detail']['status'];
		$id = $message['detail']['jobId'];

		if ($status === AwsMediaConversion::STATUS_COMPLETE) {
            $percentage = 100;
        } else {
            $percentage = $message['detail']['jobProgress']['jobPercentComplete'] ?? null;
        }

		if ($olderjob = AwsMediaConversion::whereJobId($id)->first()) {
			$model_type = $olderjob->model_type;
			$model_id = $olderjob->model_id;

			if($status === AwsMediaConversion::STATUS_COMPLETE && $olderjob->model) {
				$olderjob->model->onConversionComplete($message);
			}
		}

		$log = new AwsMediaConversion();
		$log->model_type = $model_type ?? null;
		$log->model_id = $model_id ?? null;
		$log->status = $status;
		$log->job_id = $id;
		$log->message = $message;
		$log->progress = $percentage;
		$log->save();
	}

	public function getMessage()
	{
		$raw = request()->getContent();
		Log::info('aws-mediaconvert-content', [$raw]);

		if (!isset($_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE'])) {
			throw new Exception('This is not a sns request, must have X_AMZ_SNS_MESSAGE_TYPE in header');
		}

		// to array
		$payload = \json_decode($raw, true);

		if (array_key_exists('SubscribeURL', $payload)) {
			Http::get($payload['SubscribeURL']);
		}

		return json_decode($payload['Message'], true);

	}
}
