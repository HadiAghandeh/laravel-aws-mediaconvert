<?php

namespace HadiAghandeh\AwsMediaConvert;

use HadiAghandeh\AwsMediaConvert\Models\AwsMediaConversion;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AwsMediaConvertServiceProvider extends ServiceProvider
{
	public function boot()
	{
		// routes
		$this->loadRoutesFrom(__DIR__ . "/../routes/web.php");

		// configs
		$this->publishes([
			__DIR__ . '/../configs/aws-mediaconvert.php' => config_path('aws-mediaconvert.php'),
		], 'aws-mediaconvert');

		// migrations
		$this->loadMigrationsFrom(__DIR__ . "/../database");

		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../database/2023_06_26_080727_create_aws_media_conversion_log.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_aws_media_conversions_log_table.php'),
			], 'aws-mediaconvert');
		}

		Relation::enforceMorphMap([
			'AWSMC' => AwsMediaConversion::class,
		]);

	}

	public function register()
	{
		/**
		 * register the config file so the config is available without publishing
		 */
		$this->mergeConfigFrom(__DIR__ . '/../configs/aws-mediaconvert.php', 'aws-mediaconvert');

		$this->registerFacades();
	}

	/**
	 * Register aliases.
	 *
	 * @return void
	 */
	protected function registerFacades()
	{
	}
}
