<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(!$request->user()->is_admin){
            return response()->json([
                'message' => "Unauthorized Access!"
            ]);
        }
        if($request['search'])
        {
            $bookings = Booking::where('date_booked_for', $request['search'])->with('user')->get();
        }
        else{
            $bookings = Booking::selectRaw("date_booked_for, count('*') as booking_count")->groupBy('date_booked_for')->orderBy('date_booked_for')->get();
        }
        if(!$bookings)
        {
            return response()->json([
                'message' => "No Bookings found!"
            ]);
        }
        return $bookings;
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        if(! $request->user()->is_admin)
        {
            return response()->json([
                'message' => "Unauthorized Access!"
            ]);
        }

        $booking = Booking::where('id', $id)->with('user')->first();
        return $booking;

    }

}
