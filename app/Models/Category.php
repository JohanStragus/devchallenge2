<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'categories';

    // Clave primaria personalizada
    protected $primaryKey = 'id_category';

    // Campos asignables
    protected $fillable = [
        'name',
    ];

    /**
     * RELACIONES
     */

    // Relación N:M con listas (tabla intermedia list_categories)
    public function lists()
    {
        return $this->belongsToMany(ListModel::class, 'list_categories', 'id_category', 'id_list')
                    ->withTimestamps();
    }

    // Relación 1:N con productos
    public function products()
    {
        return $this->hasMany(Product::class, 'id_category');
    }
}
