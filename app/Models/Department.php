<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $table = 'departments';

    protected $fillable = [
        'id_department',
        'name',
    ];

    // Relación con el modelo Twon
    public function twon()
    {
    return $this->hasMany(Twon::class, 'id_department');
    }
}
