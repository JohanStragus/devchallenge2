<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListCategory extends Model
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'list_categories';

    // Clave primaria personalizada
    protected $primaryKey = 'id_list_categories';

    // Campos asignables
    protected $fillable = [
        'id_list',
        'id_category',
    ];

    /**
     * RELACIONES
     */

    // Relación con la lista
    public function list()
    {
        return $this->belongsTo(ListModel::class, 'id_list');
    }

    // Relación con la categoría
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }
}
