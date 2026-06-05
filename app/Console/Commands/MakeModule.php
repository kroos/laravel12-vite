<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
		$class = class_basename($name);
		$folder = str_replace(
			'\\',
			'/',
			Str::beforeLast($name, '\\')
		);

		// dd(
		// 	$this->className($name),
		// 	$this->folder($name),
		// );

		$this->writeFile(
			app_path("Models/{$folder}/{$class}.php"),
			$this->buildStub('model.stub', [
				'namespace' => "App\\Models\\{$folder}",
				'class' => $class,
			])
		);



		// $this->call('make:model', [
		// 	'name' => $name,
		// 	'-m' => true 						//migration
		// ]);

		$this->writeFile(
			app_path("Http/Controllers/{$folder}/{$class}Controller.php"),
			$this->buildStub('controller.model.stub', [
				'namespace' => "App\\Http\\Controllers\\{$folder}",
				'requestNamespace' => $folder,
				'resourceNamespace' => $folder,
				'modelNamespace' => $folder,
				'class' => $class,
				'variable' => Str::camel($class),
			])
		);

		// $this->call('make:controller', [
		// 	'name' => "{$name}Controller",
		// 	'--resource' => true,
		// 	'--model' => $name
		// ]);

		$this->writeFile(
			app_path("Http/Requests/{$folder}/Store{$class}Request.php"),
			$this->buildStub('store-request.stub', [
				'namespace' => "App\\Http\\Requests\\{$folder}",
				'class' => $class,
			])
		);

		$this->writeFile(
			app_path("Http/Requests/{$folder}/Update{$class}Request.php"),
			$this->buildStub('update-request.stub', [
				'namespace' => "App\\Http\\Requests\\{$folder}",
				'class' => $class,
			])
		);

		// $this->call('make:request', [
		// 	'name' => "{$this->folder($name)}\\Store{$this->className($name)}Request"
		// ]);

		// $this->call('make:request', [
		// 	'name' => "{$this->folder($name)}\\Update{$this->className($name)}Request"
		// ]);

		// $this->call('make:policy', [
		// 	'name' => "{$this->className($name)}Policy",
		// 	'--model' => $name
		// ]);

		$this->writeFile(
			app_path("Policies/{$folder}/{$class}Policy.php"),
			$this->buildStub('policy.stub', [
				'namespace' => "App\\Policies\\{$folder}",
				'class' => $class,
				'modelNamespace' => $folder,
				'variable' => Str::camel($class),
			])
		);



		// $this->call('make:resource', [
		// 	'name' => "{$this->className($name)}Resource"
		// ]);

		$this->writeFile(
			app_path("Http/Resources/{$folder}/{$class}Resource.php"),
			$this->buildStub('resource.stub', [
				'namespace' => "App\\Http\\Resources\\{$folder}",
				'class' => $class,
			])
		);

		$this->call('make:migration', [
			'name' => 'create_' .
			Str::snake(Str::pluralStudly($class)) .
			'_table'
		]);

	}

	protected function className($name)
	{
		return class_basename($name);
	}

	protected function folder($name)
	{
		return explode('\\', $name)[0];
	}

	protected function buildStub(string $stub, array $replace): string
	{
		$content = file_get_contents(
			base_path("stubs/module/{$stub}")
		);

		foreach ($replace as $key => $value) {
			$content = str_replace(
				"{{ {$key} }}",
				$value,
				$content
			);
		}

		return $content;
	}

	protected function writeFile(
		string $path,
		string $content
	): void {

		if (! is_dir(dirname($path))) {
			mkdir(dirname($path), 0755, true);
		}

		file_put_contents($path, $content);
	}


}
