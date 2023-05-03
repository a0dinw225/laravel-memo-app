<?php

namespace App\Providers;

use InvalidArgumentException;
use Illuminate\Support\ServiceProvider;
use App\Services\TagServiceInterface;
use App\Services\MemoServiceInterface;
use App\Services\MemoTagServiceInterface;
use App\Repositories\TagRepositoryInterface;
use App\Repositories\MemoRepositoryInterface;
use App\Repositories\MemoTagRepositoryInterface;
use App\Services\TagService;
use App\Services\MemoService;
use App\Services\MemoTagService;
use App\Repositories\TagRepository;
use App\Repositories\MemoRepository;
use App\Repositories\MemoTagRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the application's services.
     *
     * @return void
     */
    public function register(): void
    {
        // Bind repositories and services
        $classes = [
            TagRepositoryInterface::class => TagRepository::class,
            MemoRepositoryInterface::class => MemoRepository::class,
            MemoTagRepositoryInterface::class => MemoTagRepository::class,
            TagServiceInterface::class => TagService::class,
            MemoServiceInterface::class => MemoService::class,
            MemoTagServiceInterface::class => MemoTagService::class,
        ];
        $this->registerClasses($classes);
    }

    /**
     * Register the given interface to its implementation if the interface doesn't exist.
     *
     * @param array $classes An associative array of interfaces and their implementations
     * @return void
     * @throws InvalidArgumentException If the interface does not exist
     */
    private function registerClasses(array $classes): void
    {
        foreach ($classes as $interface => $implementation) {
            if (!interface_exists($interface)) {
                throw new InvalidArgumentException("$interface is not a valid interface");
            }
            app()->bindIf($interface, $implementation);
        }
    }
}
