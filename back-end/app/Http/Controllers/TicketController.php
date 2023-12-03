<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;

use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\User;
use App\Models\Comment;

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

        $rowsPerPage = $request->pagination['rowsPerPage'];
        $page = $request->pagination['page'];


        $filter = $request->filter;
        $selectedFilter = $request->selectedfield;


        $idFilter = '';
        $nameFilter = '';
        $userFilter = '';
        $statusFilter = '';
        $prioritiesFilter = '';
        $productFilter = '';


        switch ($selectedFilter) {
            case 'ID':
                $idFilter = $filter;
                break;
            case 'Name':
                $nameFilter = $filter;
                break;
            case 'Assigned':
                $userFilter = $filter;
                break;
            case 'Status':
                $statusFilter = $filter;
                break;
            case 'Priority':
                $prioritiesFilter = $filter;
                break;
            case 'Product':
                $productFilter = $filter;
                break;
        }

        $orderBy = $request->pagination['sortBy'];
        $sortState = $request->pagination['descending'];
        $sortDirection = 'desc';

        if($sortState)
            $sortDirection = 'asc';

        if(!$orderBy)
            $orderBy = 'id';

        $tickets = DB::table('tickets')
        ->leftJoin('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.ticket_status_id')
        ->leftJoin('ticket_priorities', 'ticket_priorities.id', '=', 'tickets.ticket_priority_id')
        ->leftJoin('products', 'products.id', '=', 'tickets.product_id')
        ->leftJoin('users', 'users.id', '=', 'tickets.assigned_user_id')
        ->select('tickets.id as ID', 'tickets.name as Name', 'ticket_statuses.name as Status', 'ticket_priorities.name as Priority', 'users.name as Assigned', 'products.name as Product')
        ->where('tickets.id', 'like', '%'.$idFilter.'%' )
        ->where('tickets.name', 'like', '%'.$nameFilter.'%' )
        ->where('ticket_statuses.name', 'like', '%'.$statusFilter.'%' )
        ->where('ticket_priorities.name', 'like', '%'.$prioritiesFilter.'%' )
        ->where('users.name', 'like', '%'.$userFilter.'%' )
        ->where('products.name', 'like', '%'.$productFilter.'%' )
        ->orderBy($orderBy, $sortDirection)
        ->offset(($page * $rowsPerPage) - ($rowsPerPage))
        ->paginate($rowsPerPage);


        return response()->json($tickets, 200);
    }

    public function ticketDataSingle(Request $request)
    {
        $id = $request['id'];

        $tickets = DB::table('tickets')
        ->leftJoin('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.ticket_status_id')
        ->leftJoin('ticket_priorities', 'ticket_priorities.id', '=', 'tickets.ticket_priority_id')
        ->leftJoin('products', 'products.id', '=', 'tickets.product_id')
        ->leftJoin('users', 'users.id', '=', 'tickets.assigned_user_id')
        ->where('tickets.id', '=', $id)
        ->select('tickets.id as ID', 'tickets.name as Name',  'tickets.description as Description', 'ticket_statuses.name as Status', 'ticket_priorities.name as Priority', 'users.name as Assigned', 'products.name as Product')
        ->first();

        return response()->json($tickets, 200);
    }

    public function ticketOptions()
    {
        $ticketStatuses = TicketStatus::all();
        $ticketPriority = TicketPriority::all();
        $users = User::all();
        $data = [
            "Status" => $ticketStatuses,
            "Priority" => $ticketPriority,
            "Users" => $users,
        ];
        return response()->json($data, 200);
    }



    public function tableLength()
    {

        $count = Ticket::count();

        return response()->json($count, 200);
    }

}
