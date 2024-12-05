<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $table = 'room_types';
    protected $fillable = ['tipo']; // Estándar, Junior, Suite

    protected $primaryKey = 'room_type_id';
    public $incrementing = false;

    const TIPOS = ['Estándar', 'Junior', 'Suite'];

    // Validación de tipo de habitación
    public static function isValidRoomType($type)
    {
        return in_array($type, self::TIPOS);
    }
}
