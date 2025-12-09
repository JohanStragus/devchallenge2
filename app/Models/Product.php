<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'products';

    // Clave primaria personalizada
    protected $primaryKey = 'id_product';

    // Campos asignables
    protected $fillable = [
        'id_category',
        'name',
        'completed',
        'details',
    ];

    // Casts (para que completed sea boolean en PHP)
    protected $casts = [
        'completed' => 'boolean',
    ];

    /**
     * RELACIONES
     */

    // Cada producto pertenece a una categoría (obligatoria)
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    // Relación N:M con listas a través de list_products
    public function lists()
    {
        return $this->belongsToMany(ListModel::class, 'list_products', 'id_product', 'id_list')
                    ->withTimestamps();
    }
}
