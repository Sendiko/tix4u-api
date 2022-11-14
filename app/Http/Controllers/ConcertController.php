<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Http\Request;

class ConcertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $concert = Concert::all();
        return response()->json([
            'status' => '200',
            'message' => 'data succesfully retrieved',
            'data' => $concert
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
        $concert = Concert::create([
            'concert_id' => uniqid(),
            'concert_name' => $request->concert_name,
            'concert_date' => $request->concert_date,
            'concert_time' => $request->concert_time,
            'name_of_artist' => $request->name_of_artist,
            'stage' => $request->stage,
            'seat_capacity' => $request->seat_capacity,
        ]);
        return response()->json([
            'status' => 201,
            'message' => "data succesfully created",
            'data' => $concert
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
        $concert = Concert::find($id);
        if($concert){
            return response()->json([
                'status' => 200,
                'message' => "data successfully retrieved",
                'data' => $concert
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "$id not found",
                'data' => "null"
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
        $concert = Concert::find($id);
        if($concert){
            $concert->concert_id = $concert->concert_id;
            $concert->concert_name = $request->concert_name ? $request->concert_name : $concert->concert_name;
            $concert->concert_date = $request->concert_date ? $request->concert_date : $concert->concert_date;
            $concert->concert_time = $request->concert_time ? $request->concert_time : $concert->concert_time;
            $concert->name_of_artist = $request->name_of_artist ? $request->name_of_artist : $concert->name_of_artist;
            $concert->stage = $request->stage ? $request->stage : $concert->stage;
            $concert->seat_capacity = $request->seat_capacity ? $request->seat_capacity : $concert->seat_capacity;
            $concert->save();
            return response()->json([
                'status' => 200,
                'message' => "data successfully updated",
                'data' => $concert
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "$id not found",
                'data' => "null"
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $concert = Concert::where('id', $id)->first();
        if($concert){
            $concert->delete();
            return response()->json([
                'status' => 200,
                'message' => "data successfully deleted",
                'data' => $concert
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
