<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Event;
use App\Models\Ticket;

use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TeamEventTicketController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Ticket::class, 'ticket');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($team, $event)
    {
        $items_per_page = request()->query('itemsPerPage', 10);
        $sort_by = request()->query('sortBy', [null])[0];
        $desc = (request()->query('sortDesc', ['false'])[0] === 'true');
        $search = request()->query('search', '');

        // Search by user info
        $tickets = Ticket::join('users', 'users.id', '=', 'tickets.user_id')
            ->select('tickets.*', 'users.first_name', 'users.last_name', 'users.email')
            ->where('event_id', $event)
            ->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });

        // Order by
        if ($sort_by) {
            $sort_by = $sort_by == 'created_at' ? 'tickets.created_at' : $sort_by;
            $tickets = $tickets
                ->orderBy($sort_by, $desc ? 'desc' : 'asc');
        }


        $tickets = $tickets->paginate($items_per_page);

        return new TicketCollection($tickets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }

    // CHECKIN/CHECKOUT
    public function checkin(Request $request, $team, $event, Ticket $ticket)
    {
        $this->authorize('checkin', $ticket);

        if (is_null($ticket->checkin_at)) {
            $ticket->checkin_at = Carbon::now();
            $ticket->save();
        }

        $ticket = $ticket->load('user');

        return new TicketResource($ticket);
    }

    public function checkout(Request $request, $team, $event, Ticket $ticket)
    {
        $this->authorize('checkin', $ticket);

        if (!is_null($ticket->checkin_at)) {
            $ticket->checkin_at = null;
            $ticket->save();
        }

        $ticket = $ticket->load('user');

        return new TicketResource($ticket);
    }
}
