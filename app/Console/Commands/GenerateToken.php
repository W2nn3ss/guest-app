<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate bearer token auth for api';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $token = Str::random(60);
        $this->setEnvironmentValue('API_TOKEN', $token);
        $this->info("Bearer token generated and stored in .env: {$token}");
    }

    protected function setEnvironmentValue($key, $value): void
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            file_put_contents($path, preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                file_get_contents($path)
            ));
        }
    }
}
