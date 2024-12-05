<?php
// app/Models/Hotel.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'hotel_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'address',
        'city',
        'nit',
        'phone_number',
        'max_rooms' // Asegúrate de que 'max_rooms' se usa correctamente
    ];

    // Validación de 'max_rooms'
    public static function validateMaxRooms($maxRooms)
    {
        return $maxRooms >= 1;  // Validación para asegurarse que el valor es positivo
    }
}
