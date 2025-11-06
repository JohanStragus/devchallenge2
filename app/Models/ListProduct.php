<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListProduct extends Model
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'list_products';

    // Clave primaria personalizada
    protected $primaryKey = 'id_list_product';

    // Campos asignables
    protected $fillable = [
        'id_list',
        'id_product',
    ];

    /**
     * RELACIONES
     */

    // Una relación con la lista
    public function list()
    {
        return $this->belongsTo(ListModel::class, 'id_list');
    }

    // Una relación con el producto
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
