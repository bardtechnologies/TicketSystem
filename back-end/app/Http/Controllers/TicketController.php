<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::all();

        return response()->json($tickets, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();
        $ticket = Ticket::create($data);
        return response()->json($ticket, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return response()->json($ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $data = $request->validated();

        $ticket->update($data);

        return response()->json($ticket, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
        $ticket->delete();

        return response()->json(null, 204);
    }

    public function ticketData(Request $request)
    {
        //

        $rowsPerPage = $request->pagination['rowsPerPage'];
        $page = $request->pagination['page'];
        $tickets = DB::table('tickets')
        ->join('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.ticket_status_id')
        ->join('ticket_priorities', 'ticket_priorities.id', '=', 'tickets.ticket_priority_id')
        ->join('products', 'products.id', '=', 'tickets.product_id')
        ->join('users', 'users.id', '=', 'tickets.assigned_user_id')
        ->select('tickets.id', 'tickets.name', 'ticket_statuses.name as Status', 'ticket_priorities.name as Priority', 'users.name as Assigned', 'products.name as Product')
        ->take($rowsPerPage * 50)
        ->get();

        return response()->json($tickets, 200);
    }

}
