<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::all();
        return response()->json([
            'status' => '200',
            'message' => 'data succesfully sent',
            'data' => $tickets
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tickets = Ticket::create([
            'ticket_number' => uniqid(),
            'concert_name' => $request->concert_name,
            'concert_date' => $request->concert_date,
            'concert_time' => $request->concert_time,
            'name_of_artist' => $request->name_of_artist,
            'price' => $request->price,
            'currency' => $request->currency,
            'address' => $request->address,
            'stage' => $request->stage,
            'availability' => $request->availability
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'data successfully created',
            'data' => $tickets
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tickets = Ticket::find($id);
        if($tickets){
            return response()->json([
                'status' => 200,
                'message' => "data successfully sent",
                'data' => $tickets
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => `$id not found`,
                'data' => 'null'
            ], 404);
        };
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tickets = Ticket::find($id);
        if($tickets){
            $tickets->ticket_number = uniqid();
            $tickets->concert_name = $request->concert_name ? $request->concert_name : $tickets->concert_name;
            $tickets->concert_date = $request->concert_date ? $request->concert_date : $tickets->concert_date;
            $tickets->concert_time = $request->concert_time ? $request->concert_time : $tickets->concert_time;
            $tickets->name_of_artist = $request->name_of_artist ? $request->name_of_artist : $tickets->name_of_artist;
            $tickets->price = $request->price ? $request->price : $tickets->price;
            $tickets->currency = $request->currency ? $request->currency : $tickets->currency;
            $tickets->address = $request->address ? $request->address : $tickets->address;
            $tickets->stage = $request->stage ? $request->stage : $tickets->stage;
            $tickets->availability = $request->availability ? $request->availability : $tickets->availability;
            $tickets->save();
            return response()->json([
                'status' => 200,
                'message' => 'data successfully updated',
                'data' => $tickets
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => `$id not found`, 
                'data' => 'null'
            ]);
        };
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tickets = Ticket::where('id', $id)->first();
        if($tickets){
            $tickets->delete();
            return response()->json([
                'status' => 200,
                'message' => "data successfully deleted",
                'data' => $tickets
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "$id not found",
                'data' => 'null'
            ], 404);
        }
    }
}
