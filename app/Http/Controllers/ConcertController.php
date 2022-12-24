<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\Artist;
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
        $data = $request->validate([
            'concert_name' => 'required|string|max:255',
            'concert_date' => 'required|date',
            'concert_time' =>  'required|string',
            'concert_address' =>  'required|string|max:255',
            'artist_id' => 'required|integer',
            'stage' => 'required|string',
            'seat_capacity' => 'required|integer',
            'role' => 'required|in:ARTISTS,DEVS,USERS'
        ]);

        $artist_id = $request->artist_id;
        $artist = Artist::find($artist_id);
        if($data['role'] === "ARTISTS" || $data['role'] === "DEVS"){
            if($artist){
                $concert = Concert::create([
                    'concert_id' => uniqid(),
                    'concert_name' => $data['concert_name'],
                    'concert_date' => $data['concert_date'],
                    'concert_time' => $data['concert_time'],
                    'concert_address' => $data['concert_address'],
                    'name_of_artist' => $artist->artist_stagename,
                    'stage' => $data['stage'],
                    'seat_capacity' => $data['seat_capacity'],
                ]);
                return response()->json([
                    'status' => 201,
                    'message' => "data succesfully created",
                    'data' => $concert
                ], 201);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "artist not found",
                    'data' => "artist with artist id $artist_id not found"
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
                'message' => "concert not found",
                'data' => "concert with concert id $id not found"
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
        $data = $request->validate([
            'role' => 'required|in:ARTISTS,DEVS,USERS',
            'artist_id' => 'required|integer'
        ]);
        $artist = Artist::find($data['artist_id']);

        if($data['role'] === "ARTISTS" || $data['role'] === "DEVS"){
            if($concert){
                if($artist){
                    $concert->concert_id = $concert->concert_id;
                    $concert->concert_name = $request->concert_name ? $request->concert_name : $concert->concert_name;
                    $concert->concert_date = $request->concert_date ? $request->concert_date : $concert->concert_date;
                    $concert->concert_time = $request->concert_time ? $request->concert_time : $concert->concert_time;
                    $concert->concert_address = $request->concert_address ? $request->concert_address : $concert->concert_address;
                    $concert->name_of_artist = $artist->artist_stagename;
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
                        'message' => "artist not found",
                        'data' => "artrist with artist id $request->artist_id not found"
                    ], 404);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "concert not found",
                    'data' => "concert with concert id $id not found"
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
        $concert = Concert::where('id', $id)->first();
        
        if($role['role'] === "ARTISTS" || $role['role'] === "DEVS"){
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
                    'message' => "concert not found",
                    'data' => "concert with concert id $id not found"
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
