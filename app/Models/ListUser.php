<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListUser extends Model
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'list_users';

    // Clave primaria personalizada
    protected $primaryKey = 'id_list_users';

    // Campos asignables
    protected $fillable = [
        'id_list',
        'id_user',
        'role',
    ];

    /**
     * RELACIONES
     */

    // Relación con la lista
    public function list()
    {
        return $this->belongsTo(ListModel::class, 'id_list');
    }

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
