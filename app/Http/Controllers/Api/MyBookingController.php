<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Gate;


class MyBookingController extends Controller
{
     /**
     * Display bookings of the logged in customer.
     */
    public function index(Request $request)
    {
        if($request->user()->is_admin)
        {
            return response()->json([
                'message' => "Unexpected Request from Admin!"
            ]);
        }
        $mybookings = Booking::where('user_id', $request->user()->id)->paginate(15);
        if(count($mybookings) > 0){
            return $mybookings;
        }
        else{
            return response()->json([
                'message' => "You don't have any Bookings!"
            ]);
        }
    }

    // Stores new Booking

    public function store(Request $request)
    {
        if($request->user()->is_admin)
        {
            return response()->json([
                'message' => "Unexpected Request from Admin!"
            ]);
        }
        $request->validate([
            'date_booked_for' => 'required|date_format:Y-m-d',
            'purpose'=> 'required|string|max:500',
        ],[],[
            'date_booked_for' => 'Date for Appointment',
            'purpose' => 'Purpose of Appointment'
        ]);
        Booking::create([
            'date_booked_for' => $request['date_booked_for'],
            'purpose' => $request['purpose'],
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            "message" => "Booking done successfully!"
        ]);
    }

    /**
     * Display the specified booking.
     */
    public function show(Request $request, string $id)
    {
        $booking = $this->checkAccess($request, $id);
        if(is_string($booking))
        {
            return response()->json([
                "message" => $booking
            ]);
        }
        return $booking;
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, string $id)
    {
        $booking = $this->checkAccess($request, $id);
        if(is_string($booking))
        {
            return response()->json([
                "message" => $booking
            ]);
        }

        $request->validate([
            'date_booked_for' => 'sometimes|date_format:Y-m-d',
            'purpose'=> 'sometimes|string|max:500',
        ],[],[
            'date_booked_for' => 'Date for Appointment',
            'purpose' => 'Purpose of Appointment'
        ]);

        if($request['date_booked_for'])
        {
            $booking->date_booked_for = date_format(date_create($request['date_booked_for']), 'Y-m-d');
        }
        if($request['purpose'])
        {
            $booking->purpose = $request['purpose'];
        }

        $booking->save();

        return response()->json([
            "message" => "Booking updated successfully!"
        ]);
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $booking = $this->checkAccess($request, $id);
        if(is_string($booking))
        {
            return response()->json([
                "message" => $booking
            ]);
        }

        $booking->delete();

        return response()->json([
            "message" => "Booking deleted Successfully!"
        ]);
    }

    // Checking access whether admin or booking created by any other user

    public function checkAccess(Request $request, string $id)
    {
        if($request->user()->is_admin)
        {
            return  "Unexpected Request from Admin!";
        }

        $booking = Booking::findorFail($id);

        if (! Gate::allows('is-mybooking', $booking))
        {
            return "Unauthorized access to content!";
        }

        return $booking;
    }


}
