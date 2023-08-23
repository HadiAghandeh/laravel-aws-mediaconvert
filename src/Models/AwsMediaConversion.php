<?php

namespace HadiAghandeh\AwsMediaConvert\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AwsMediaConversion extends Model
{
	const STATUS_SUBMITTED = "SUBMITTED";
	const STATUS_PROGRESSING = "PROGRESSING";
	const STATUS_COMPLETE = "COMPLETE";
	const STATUS_ERROR= "ERROR";
	const STATUS_CANCELED= "CANCELED";
	const STATUS_INPUT_INFORMATION= "INPUT_INFORMATION";
	const STATUS_STATUS_UPDATE= "STATUS_UPDATE";
	const STATUS_NEW_WARNING= "NEW_WARNING";

    use HasFactory;

	protected $fillable = ['job_id'];

	protected $table = "aws_media_conversions";

	protected $casts = [
        'message' => 'array',
    ];

	public function model(): MorphTo
    {
        return $this->morphTo();
    }

}
