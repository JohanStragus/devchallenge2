<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ListModel;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atributos asignables en masa (mass assignable)
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'phone',
        'address',
    ];

    /**
     * Atributos ocultos (no se devuelven en JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts para atributos (conversión automática)
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     *  === RELACIONES ===
     */

    // Un usuario puede tener muchas listas propias (como propietario)
    public function lists()
    {
        return $this->hasMany(ListModel::class, 'id_user');
    }

    // Un usuario puede estar en muchas listas compartidas (tabla pivot list_users)
    public function sharedLists()
    {
        return $this->belongsToMany(ListModel::class, 'list_users', 'id_user', 'id_list')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // Un usuario puede hacer muchos comentarios
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_user');
    }
}
