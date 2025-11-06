<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'comments';

    // Clave primaria personalizada
    protected $primaryKey = 'id_comment';

    // Campos asignables
    protected $fillable = [
        'id_list',
        'id_user',
        'content',
    ];

    /**
     * RELACIONES
     */

    // Un comentario pertenece a una lista
    public function list()
    {
        return $this->belongsTo(ListModel::class, 'id_list');
    }

    // Un comentario pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
