<?php
// app/Http/Controllers/HotelController.php
// app/Http/Controllers/HotelController.php
namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Accommodation;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function store(Request $request)
    {
        // Validación de los datos sin la regla 'unique' para 'nit'
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'nit' => 'required|string', 
            'phone_number' => 'nullable|string',  
            'max_rooms' => 'required|integer|min:1'  
        ]);
    
        // Verificar si el hotel ya existe por NIT
        $existingHotel = Hotel::where('nit', $request->nit)->first();
        if ($existingHotel) {
            // Si existe un hotel con el mismo NIT, responder con error 409
            return response()->json(['error' => 'El hotel con este NIT ya existe.'], 409);  // Error 409 por conflicto
        }
    
        // Crear el hotel solo con los campos permitidos
        try {
            // Aquí usamos `only()` para asegurarnos de que solo los campos correctos se pasen al modelo
            $hotel = Hotel::create($request->only(['name', 'address', 'city', 'nit', 'phone_number', 'max_rooms']));
    
            return response()->json([
                'success' => true,  // Indica que la operación fue exitosa
                'message' => 'Hotel creado exitosamente',  // Mensaje de éxito
                'hotel' => $hotel  // Datos del hotel creado
            ], 201); // Devolver el hotel creado con un código 201
        } catch (\Exception $e) {
            // Detallar el error real para depuración (opcional)
            return response()->json([
                'error' => 'Ocurrió un problema al crear el hotel.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    public function getAvailableRooms()
    {
        // Obtenemos todas las habitaciones 
        $rooms = Room::with('hotel', 'roomType', 'accommodation')  // Cargar las relaciones de hotel, tipo de habitación y acomodación
        ->get();

        // Retornamos las habitaciones 
        return response()->json($rooms);
    }
    
    

    public function index()
    {
        // Obtener todos los hoteles
        return response()->json(Hotel::all());
    }

    // Método para agregar habitaciones a un hotel específico
    public function addRoom($hotel_id, Request $request)
    {
        // Verificar que el hotel exista
        $hotel = Hotel::find($hotel_id);
        if (!$hotel) {
            return response()->json(['error' => 'Hotel no encontrado'], 404);
        }

        // Validación de los datos de la habitación
        $request->validate([
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'accommodation_id' => 'required|exists:accommodations,accommodation_id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Verificar si la cantidad de habitaciones supera el límite máximo del hotel
        $totalRoomsAssigned = Room::where('hotel_id', $hotel_id)->sum('quantity');
        if ($totalRoomsAssigned + $request->quantity > $hotel->max_rooms) {
            return response()->json(['invalid' => 'La cantidad total de habitaciones supera el límite máximo del hotel.'], 400);
        }

        // Verificar las reglas de negocio sobre tipos de habitaciones y acomodaciones
        if (!$this->isValidAccommodation($request->room_type_id, $request->accommodation_id)) {
            return response()->json(['invalid' => 'Acomodación no válida para este tipo de habitación'], 400);
        }

        // Verificar si ya existe una habitación con la misma combinación
        $existingRoom = Room::where('hotel_id', $hotel_id)
            ->where('room_type_id', $request->room_type_id)
            ->where('accommodation_id', $request->accommodation_id)
            ->first();

        if ($existingRoom) {
            // Si existe, actualizar la cantidad
            $existingRoom->quantity += $request->quantity;
            $existingRoom->save();

            return response()->json([
                'message' => 'Habitación actualizada exitosamente',
                'room' => $existingRoom
            ], 200);
        }

        // Si no existe, crear una nueva habitación
        $room = new Room();
        $room->hotel_id = $hotel_id;
        $room->room_type_id = $request->room_type_id;
        $room->accommodation_id = $request->accommodation_id;
        $room->quantity = $request->quantity;
        $room->save();

        return response()->json([
            'message' => 'Habitación creada exitosamente',
            'room' => $room
        ], 201);
    }

    /**
     * Verifica si la combinación de tipo de habitación y acomodación es válida.
     * 
     * @param int $roomTypeId
     * @param int $accommodationId
     * @return bool
     */
    private function isValidAccommodation($roomTypeId, $accommodationId)
    {
        $roomType = RoomType::find($roomTypeId);
        $accommodation = Accommodation::find($accommodationId);

        // Definir las combinaciones válidas
        $validCombinations = [
            'Estándar' => ['Sencilla', 'Doble'],
            'Junior' => ['Triple', 'Cuádruple'],
            'Suite' => ['Sencilla', 'Doble', 'Triple']
        ];

        // Comprobar si la combinación es válida
        return in_array($accommodation->name, $validCombinations[$roomType->name] ?? []);
    }
}
