<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Concert;
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
        $data = $request->validate([
            'concert_id' => 'required|integer',
            'price' => 'required|integer',
            'currency' => 'required|string',
            'seat_number' => 'required|string',
            'role' => 'required|in:ARTISTS,DEVS,USERS'
        ]);

        $concert = Concert::find($data['concert_id']);
        if($data['role'] === "ARTISTS" || $data['role'] === "DEVS"){
            if($concert){
                $tickets = Ticket::create([
                    'ticket_number' => uniqid(),
                    'concert_name' => $concert->concert_name,
                    'concert_date' => $concert->concert_date,
                    'concert_time' => $concert->concert_time,
                    'concert_address' => $concert->concert_address,
                    'name_of_artist' => $concert->name_of_artist,
                    'stage' => $concert->stage,
                    'price' => $request->price,
                    'currency' => $request->currency,
                    'seat_number' => $request->seat_number,
                ]);
                return response()->json([
                    'status' => 201,
                    'message' => 'data successfully created',
                    'data' => $tickets
                ], 201);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'concert not found',
                    'data' => "concert with $request->concert_id not found"
                ], 404);
            }    
        } else {
            return response()->json([
                'status' => 405,
                'message' => "permission denied",
                'data' => "$request->role don't have permission to this method"
            ], 405);
        }
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
                'message' => "ticket not found",
                'data' => "ticket with id $id not found"
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
        $data = $request->validate([
            'concert_id' => 'required|integer',
            'price' => 'integer',
            'currency' => 'string',
            'seat_number' => 'string',
            'role' => 'required|in:ARTISTS,DEVS,USERS'
        ]);

        $concert = Concert::find($data['concert_id']);
        $tickets = Ticket::find($id);
        if($data['role'] === "ARTISTS" || $data['role'] === "DEVS"){
            if($tickets){
                $tickets->ticket_number = uniqid();
                $tickets->concert_name = $concert->concert_name;
                $tickets->concert_date = $concert->concert_date;
                $tickets->concert_time = $concert->concert_time;
                $tickets->concert_address = $concert->concert_address;
                $tickets->name_of_artist = $concert->name_of_artist;
                $tickets->stage = $concert->stage;
                $tickets->price = $request->price ? $request->price : $tickets->price;
                $tickets->currency = $request->currency ? $request->currency : $tickets->currency;
                $tickets->seat_number = $request->seat_number ? $request->seat_number : $tickets->seat_number;
                $tickets->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'data successfully updated',
                    'data' => $tickets
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "ticket id not found", 
                    'data' => "ticket with id $id not found"
                ], 404);
            };
        } else {
            return response()->json([
                'status' => 405,
                'message' => "permission denied",
                'data' => "$request->role don't have permission to this method"
            ], 405);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $role = $request->validate([
            'role' => 'required|in:ARTISTS,DEVS,USERS'
        ]);

        $tickets = Ticket::where('id', $id)->first();
        if($role['role'] === "ARTISTS" || $role['role'] === "DEVS"){
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
                    'message' => "ticket id $id not found",
                    'data' => 'null'
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 405,
                'message' => "permission denied",
                'data' => "$request->role don't have permission to this method"
            ], 405);
        }
    }
}
