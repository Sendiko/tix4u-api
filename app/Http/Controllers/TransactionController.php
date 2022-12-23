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
        $data = $request->validate([
            'ticket_id' => 'required|integer',
            'amount_of_ticket' => 'required|integer'
        ]);

        $ticket = Ticket::find($data['ticket_id']);
        $ticket_price = $data['amount_of_ticket'] * $ticket->price;
        $tax = $ticket_price * (11/100);
        $total_price = $ticket_price + $tax;
            $transactions = Transaction::create([
                'transaction_id' => uniqid(),
                'amount_of_ticket' => $request->amount_of_ticket,
                'ticket_price' => $ticket_price,
                'total_price' => $total_price,
                'concert_name' => $ticket->concert_name,
                'concert_address' => $ticket->address,
                'concert_date' => $ticket->concert_date,
                'currency' => $ticket->currency
            ]);
            return response()->json([
                'status' => 200,
                'message' => "data successfully created",
                'data' => $transactions
            ], 200);

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
                'message' => "transaction not found",
                'data' => "transaction with $id not found"
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
            'ticket_id' => 'required|integer',
            'amount_of_ticket' => 'required|integer'

        ]);

        $transactions = Transaction::find($id);
        $ticket = Ticket::find($data['ticket_id']);
        $ticket_price = $data['amount_of_ticket'] * $ticket->price;
        $tax = $ticket_price * (11/100);
        $total_price = $ticket_price + $tax;
        if($transactions){
            $transactions->transaction_id = $transactions->transaction_id;
            $transactions->total_price = $total_price;
            $transactions->amount_of_ticket = $request->amount_of_ticket;
            $transactions->ticket_price = $ticket_price;
            $transactions->concert_name = $ticket->concert_name;
            $transactions->concert_address = $ticket->address;
            $transactions->concert_date = $ticket->concert_date;
            $transactions->currency = $ticket->currency;
            $transactions->save();
            return response()->json([
                'status' => 200,
                'message' => 'data successfully updated',
                'data' => $transactions
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "transaction not found", 
                'data' => "transaction with $id not found",
            ], 404);
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
                'message' => "transaction not found",
                'data' => "transaction with $id not found"
            ], 404);
        }
    }
}
