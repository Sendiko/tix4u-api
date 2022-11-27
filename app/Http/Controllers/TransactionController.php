<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = Transaction::all();
        return response()->json([
            'status' => '200',
            'message' => 'data succesfully sent',
            'data' => $transaction
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
        $ticket = Ticket::find($request->id);
        $ticket_price = $ticket->price;
        $total_price = $request->amount_of_ticket * $ticket_price;
        if($total_price != 0){
            $tax = $total_price * (1/100);
            $transactions = Transaction::create([
                'transaction_id' => uniqid(),
                'amount_of_ticket' => $request->amount_of_ticket,
                'ticket_price' => $ticket_price,
                'total_price' => $total_price,
                'concert_name' => $ticket->concert_name,
                'concert_address' => $ticket->address,
                'concert_date' => $ticket->concert_date,
                'tax' => $tax,
                'currency' => $ticket->currency
            ]);
            return response()->json([
                'status' => 200,
                'message' => "data successfully created",
                'data' => $transactions
            ], 200);
        } else {
            return response()->json([
                'status' => 406,
                'message' => "total_price can't be 0",
                'data' => 'null'
            ], 406);
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
        $transactions = Transaction::find($id);
        if($transactions){
            return response()->json([
                'status' => 200,
                'message' => "data successfully sent",
                'data' => $transactions
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "transaction id $id  not found",
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
        if($request->id != null){
            $transactions = Transaction::find($id);
            $ticket = Ticket::find($request->id);
            $ticket_price = $ticket->price;
            $total_price = $request->amount_of_ticket * $ticket_price;
            $tax = $total_price * (1/100);
            if($transactions){
                if($request->amount_of_ticket != 0){
                    $transactions->transaction_id = $transactions->transaction_id;
                    $transactions->total_price = $total_price;
                    $transactions->amount_of_ticket = $request->amount_of_ticket;
                    $transactions->ticket_price = $ticket_price;
                    $transactions->concert_name = $ticket->concert_name;
                    $transactions->concert_address = $ticket->address;
                    $transactions->concert_date = $ticket->concert_date;
                    $transactions->tax = $tax;
                    $transactions->currency = $ticket->currency;
                    $transactions->save();
                    return response()->json([
                        'status' => 200,
                        'message' => 'data successfully updated',
                        'data' => $transactions
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 418,
                        'message' => "amount_of_id can't be 0", 
                        'data' => 'null'
                    ], 418);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "transaction id $id not found", 
                    'data' => 'null'
                ], 404);
            };
        } else {
            return response()->json([
                'status' => 418,
                'message' => "ticket_id is required", 
                'data' => 'null'
            ], 418);
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
        $transactions = Transaction::where('id', $id)->first();
        if($transactions){
            $transactions->delete();
            return response()->json([
                'status' => 200,
                'message' => "data successfully deleted",
                'data' => $transactions
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "transaction id $id not found",
                'data' => 'null'
            ], 404);
        }
    }
}
