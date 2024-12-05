<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    use HasFactory;

    protected $table = 'accommodations';
    protected $fillable = ['acomodacion']; // Sencilla, Doble, Triple, Cuádruple

    protected $primaryKey = 'accommodation_id';
    public $incrementing = false;

    // Constantes para los tipos de acomodación
    const ACOMODACIONES = [
        'Sencilla', 'Doble', 'Triple', 'Cuádruple'
    ];

    // Método de validación para verificar si el valor está en las acomodaciones disponibles
    public static function isValidAccommodation($value)
    {
        return in_array($value, self::ACOMODACIONES);
    }
}
