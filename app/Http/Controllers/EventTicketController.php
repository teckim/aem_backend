<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Event;

class EventTicketController extends Controller
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
    public function index(Event $event)
    {
        $items_per_page = request()->query('itemsPerPage', 10);
        $sort_by = request()->query('sortBy', [null])[0];
        $desc = (request()->query('sortDesc', ['false'])[0] === 'true');
        $search = request()->query('search', '');
        

        $tickets = Ticket::join('users', 'users.id', '=', 'tickets.user_id')
            ->where('event_id', $event->id)
            ->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
            });

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($event_id, $ticket_id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function chekin(Request $request, Ticket $ticket)
    {
        $this->authorize('update_ticket_checkin');
    }
}
