<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewHallSeatRequest;
use App\Http\Resources\HallResource;
use App\Http\Resources\HallSeatResource;
use App\Models\Hall;
use App\Models\SeatClass;
use Illuminate\Http\Request;

class HallSeatController extends Controller
{

    public function index(Hall $hall)
    {

        return response()->json([
            'data' => [
                'hall_seats' => new HallSeatResource($hall)
            ]
        ])->setStatusCode(200);
    }


    public function store(Hall $hall , NewHallSeatRequest $request)
    {
        $seats = $request->get('seats');

        $requestedSeatCount = collect($seats)->sum('seat_count');

        if ($requestedSeatCount > $hall->seat_count) {
            return response()->json([
                'errors' => [
                    'capacity' => 'request seat count is greater than hall capacity'
                ]
            ])->setStatusCode(400);
        }

        foreach ($seats as $seat) {

            $id = $seat['seat_class_id'];

            unset($seat['seat_class_id']);

            $hall->seats()->attach($id,$seat);
        }

        return response()->json([
            'data' => [
                'hall' => new HallSeatResource($hall)
            ]
        ])->setStatusCode(200);

    }

    public function update(Hall $hall, NewHallSeatRequest $request)
    {

        $hall->seats()->detach();

        $seats = $request->get('seats');

        $requestedSeatCount = collect($seats)->sum('seat_count');

        if ($requestedSeatCount > $hall->seat_count) {
            return response()->json([
                'errors' => [
                    'capacity' => 'request seat count is greater than hall capacity'
                ]
            ])->setStatusCode(400);
        }

        foreach ($seats as $seat) {

            $id = $seat['seat_class_id'];

            unset($seat['seat_class_id']);

            $hall->seats()->attach($id,$seat);
        }

        return response()->json([
            'data' => [
                'hall' => new HallSeatResource($hall)
            ]
        ])->setStatusCode(200);

    }

    public function destroy(Hall $hall , SeatClass $seatClass)
    {

        $hall->seats()->detach($seatClass);

        return response()->json([
            'data' => [
                'message' => $seatClass->title . ' seat has been deleted successfully',
                'hall' => new HallSeatResource($hall)
            ]
        ])->setStatusCode(200);
    }

}
