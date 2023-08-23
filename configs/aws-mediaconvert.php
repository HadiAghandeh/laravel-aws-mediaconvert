<?php

return [
	/*
    |--------------------------------------------------------------------------
    | AWS Secret and Key
    |--------------------------------------------------------------------------
    |
    | This is a credentail to access AWS
    |
    */

    'credentials' => [
        'id' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
    ],

	/*
    |--------------------------------------------------------------------------
    | AWS Region
    |--------------------------------------------------------------------------
    |
    | The default region of your account, example:  us-east-2
    |
    */

    'region' => env('AWS_DEFAULT_REGION'),

	/*
    |--------------------------------------------------------------------------
    | AWS MediaConvert endpoint
    |--------------------------------------------------------------------------
    |
    | The API endpoint of your MediaConvert, example: https://xxxxxxxxx.mediaconvert.us-east-2.amazonaws.com
    |
    */
    'endpoint' => env('AWS_MEDIACONVERT_ACCOUNT_URL'),
	/*
    |--------------------------------------------------------------------------
    | AWS IAM Role
    |--------------------------------------------------------------------------
    |
    | The IAM Role
    |
    */
    'iam' => env('AWS_IAM_ARN'),
    'queue' => env('AWS_QUEUE_ARN'),
    'version' => 'latest',
    'source_bucket' => env('AWS_BUCKET'),
    'dest_bucket' => env('AWS_BUCKET')
];
