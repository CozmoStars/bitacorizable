<?php

namespace CozmoStars\Bitacorizable;

use Illuminate\Support\ServiceProvider;

class BitacorizableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Permite publicar la migración
        $this->publishes([
            __DIR__ . '/database/migrations/create_bitacoras_table.php.stub' =>
                database_path('migrations/' . date('Y_m_d_His', time()) . '_create_bitacoras_table.php'),
        ], 'bitacorizable-migrations');
    }
}