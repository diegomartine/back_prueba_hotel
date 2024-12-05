<?php
use App\Http\Controllers\HotelController;
use App\Models\Accommodation;
use App\Models\RoomType;

Route::post('/hoteles', [HotelController::class, 'store']);
Route::post('/hoteles/{hotel_id}/rooms', [HotelController::class, 'addRoom']);
Route::get('/hoteles', [HotelController::class, 'index']);
// Ruta para obtener todas las acomodaciones
Route::get('/accommodations', function () {return response()->json(Accommodation::all());});

// Ruta para obtener todos los tipos de habitaciones
Route::get('/room-types', function () {return response()->json(RoomType::all());});

Route::get('/hoteles/rooms', [HotelController::class, 'getAvailableRooms']);
