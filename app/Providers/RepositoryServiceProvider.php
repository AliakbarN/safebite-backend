<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Set the root directory where your repositories are stored
        $repositoryPath = app_path('Repositories');

        // Get all PHP files in the repository directory
        $files = File::allFiles($repositoryPath);

        foreach ($files as $file) {
            // Get the fully qualified class name
            $className = $this->getClassFromFile($file->getPathname());

            if ($className) {
                // Register the class as a singleton
                $this->app->singleton($className, function ($app) use ($className)
                {
                    return new $className();
                });
            }
        }
    }

    /**
     * Get the fully qualified class name from a file.
     */
    private function getClassFromFile(string $filePath): ?string
    {
        $content = file_get_contents($filePath);

        if (preg_match('/namespace\s+(.+?);/', $content, $namespaceMatch) &&
            preg_match('/class\s+(\w+)/', $content, $classMatch)) {
            $namespace = $namespaceMatch[1];
            $className = $classMatch[1];

            return $namespace . '\\' . $className;
        }

        return null;
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

