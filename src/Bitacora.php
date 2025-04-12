<?php

namespace CozmoStars\Bitacorizable;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    /**
     * Nombre de la tabla asociada al modelo
     *
     * @var String
     */
    protected $table = 'bitacoras';

    public const UPDATE = 'update';
    public const CREATE = 'create';
    public const DELETE = 'delete';

    protected $fillable = [
        'user_id',
        'model_type',
        'model_id',
        'mensaje',
        'codigo',
        'log',
    ];

    protected $casts = [
        'log' => 'array'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function model()
    {
        return $this->morphTo();
    }
}
