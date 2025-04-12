<?php

namespace CozmoStars\Bitacorizable;

use Illuminate\Support\ServiceProvider;

class BitacorizableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Carga migraciones
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }
}