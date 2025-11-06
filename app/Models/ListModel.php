<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListModel extends Model
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'lists';

    // PK (por defecto es 'id')
    protected $primaryKey = 'id';

    // Campos asignables
    protected $fillable = [
        'id_user',   // FK propietario → users.id
        'name',
    ];

    /**
     * RELACIONES
     */

    // Propietario (dueño) de la lista
    public function owner()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Miembros (usuarios compartidos) vía pivot list_users
    public function members()
    {
        return $this->belongsToMany(User::class, 'list_users', 'id_list', 'id_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // Categorías asociadas (N:M) vía list_categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'list_categories', 'id_list', 'id_category')
                    ->withTimestamps();
    }

    // Productos asociados (N:M) vía list_products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'list_products', 'id_list', 'id_product')
                    ->withTimestamps();
    }

    // Comentarios de la lista
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_list');
    }
}
