<?php
namespace CozmoStars\Bitacorizable;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use CozmoStars\Bitacorizable\Bitacora;

trait Bitacorizable
{
    public static function bootBitacorizable()
    {
        static::created(function ($model) {
            $camposIgnorados = static::camposIgnorados($model);
            $log = collect($model->getAttributes())
                ->except($camposIgnorados)
                ->toArray();

            DB::afterCommit(function () use ($model, $log) {
                Bitacora::create([
                    'user_id' => optional(Auth::user())->id,
                    'model_type' => get_class($model),
                    'model_id' => $model->getKey(),
                    'mensaje' => 'Registro creado',
                    'codigo' => Bitacora::CREATE,
                    'log' => $log,
                ]);
            });
        });

        static::updated(function ($model) {
            $camposIgnorados = ['updated_at', 'created_at','password'];
            $log = [];

            foreach (array_keys($model->getChanges()) as $campo) {
                if ($model->wasChanged($campo) && !in_array($campo, $camposIgnorados)) {
                    $log[$campo] = [
                        'antes' => $model->getOriginal($campo),
                        'después' => $model->{$campo},
                    ];
                }
            }

            if (!empty($log)) {
                DB::afterCommit(function () use ($model, $log) {
                    Bitacora::create([
                        'user_id' => optional(Auth::user())->id,
                        'model_type' => get_class($model),
                        'model_id' => $model->getKey(),
                        'mensaje' => 'Actualización de registro',
                        'codigo' => Bitacora::UPDATE,
                        'log' => $log,
                    ]);
                });
            }
        });

        static::deleted(function ($model) {
            DB::afterCommit(function () use ($model) {
                Bitacora::create([
                    'user_id' => optional(Auth::user())->id,
                    'model_type' => get_class($model),
                    'model_id' => $model->getKey(),
                    'mensaje' => 'Eliminación de registro',
                    'codigo' => Bitacora::DELETE,
                    'log' => $model->getOriginal(),
                ]);
            });
        });
    }


    protected static function camposIgnorados($model): array
    {
        return array_merge(
            ['updated_at', 'created_at'],
            $model->bitacoraCamposIgnorados ?? []
        );
    }

    /**
     * Crea relación polimórfica con bitacoras
     */
    public function bitacora(): MorphMany
    {
        return $this->morphMany(Bitacora::class, 'model');
    }
}
