<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $fillable = ['hotel_id', 'room_type_id', 'accommodation_id', 'quantity'];

    public $timestamps = false;
    protected $primaryKey = 'room_id';
    public $incrementing = true;

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

   // Método para obtener las habitaciones disponibles de todos los hoteles
    public static function getAvailableRooms()
    {
        // Obtener el total de habitaciones disponibles de todos los hoteles
        $totalRooms = Room::sum('quantity');  // Sumamos la cantidad de habitaciones de todos los hoteles
        
        // Obtener el total de habitaciones ocupadas de todos los hoteles
        $occupiedRooms = DB::table('bookings')  // Suponiendo que tienes una tabla 'bookings' para las reservas
                            ->sum('quantity');
        
        // Calcular las habitaciones disponibles en total
        return max(0, $totalRooms - $occupiedRooms);  // Aseguramos que no sea negativo
    }


    // Validación mejorada
    public static function validateRoom($data)
    {
        // Recuperar el tipo de habitación y acomodación
        $roomType = RoomType::find($data['room_type_id']);
        $accommodation = Accommodation::find($data['accommodation_id']);

        // Validar que la combinación de tipo de habitación y acomodación sea válida
        $validCombinations = [
            'Estándar' => ['Sencilla', 'Doble'],
            'Junior' => ['Triple', 'Cuádruple'],
            'Suite' => ['Sencilla', 'Doble', 'Triple']
        ];

        if (!in_array($accommodation->acomodacion, $validCombinations[$roomType->tipo])) {
            return false; // Combinación no válida
        }

        // Validar que no se exceda el número máximo de habitaciones del hotel
        $hotel = Hotel::find($data['hotel_id']);
        $totalRooms = Room::where('hotel_id', $data['hotel_id'])->sum('quantity');

        if ($totalRooms + $data['quantity'] > $hotel->max_rooms) {
            return false; // Excede el número máximo de habitaciones
        }

        return true;
    }
}
