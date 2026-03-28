<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeModule extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	// protected $signature = 'app:make-module';
	protected $signature = 'make:module {name}';


	/**
	 * The console command description.
	 *
	 * @var string
	 */
	// protected $description = 'Command description';
	protected $description = 'Generate a full module (model, controller, requests, policy, resource)';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$name = $this->argument('name');

		$this->call('make:model', [
			'name' => $name,
			'-m' => true 						//migration
		]);

		$this->call('make:controller', [
			'name' => "{$name}Controller",
			'--resource' => true,
			'--model' => $name
		]);

		// $this->call('make:request', [
		// 	'name' => "Store{$this->className($name)}Request"
		// ]);

		// $this->call('make:request', [
		// 	'name' => "Update{$this->className($name)}Request"
		// ]);

		// $this->call('make:policy', [
		// 	'name' => "{$this->className($name)}Policy",
		// 	'--model' => $name
		// ]);

		// $this->call('make:resource', [
		// 	'name' => "{$this->className($name)}Resource"
		// ]);
	}

	protected function className($name)
	{
		return class_basename($name);
	}

}
