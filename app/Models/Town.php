<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    //
    protected $table = 'towns';

    protected $fillable = [
        'id_department',
        'name',
    ];

    // Relación con el modelo Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department');
    }
}
