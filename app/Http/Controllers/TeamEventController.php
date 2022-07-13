<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use App\Notifications\NewEvent;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventCollection;

class TeamEventController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api')->except('show');
        $this->authorizeResource(Event::class, 'event');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Team $team)
    {
        $items_per_page = request()->query('itemsPerPage', 10);
        $sort_by = request()->query('sortBy', [null])[0];
        $desc = (request()->query('sortDesc', ['false'])[0] === 'true');
        $search = request()->query('search', '');
        $state = request()->query('state', 'all');

        $events = $team->events()->with(['category', 'location']);

        // Sort by
        if ($sort_by) $events = $events->orderBy($sort_by, $desc ? 'desc' : 'asc');

        // Filter by state: soon, live, past, all
        switch ($state) {
            case 'soon':
                $events = $events->where('start_at', '>', Carbon::now());
                break;
            case 'past':
                $events = $events->where('end_at', '<', Carbon::now());
                break;
            case 'live':
                $events = $events
                    ->where('start_at', '<=', Carbon::now())
                    ->where('end_at', '>=', Carbon::now());
                break;
        }

        // Search by title
        $events = $events->where('title', 'like', '%' . $search . '%')
            ->paginate($items_per_page);

        return new EventCollection($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request, $team)
    {
        $request->merge([
            'id' => generateID(Event::class)
        ]);

        $data = $request->all();
        $data['start_at'] = Carbon::parse($data['start_at']);
        $data['end_at'] = Carbon::parse($data['end_at']);
        $data['team_id'] = $team;

        $event = Event::create($data);

        // Notify users.
        $users = User::all();
        foreach ($users as $user) {
            Notification::route('mail', $user->email)
                ->notify(new NewEvent($event, $user));
        }

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show($team, Event $event)
    {
        $event = $event->load(['category', 'location', 'team']);

        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, $team, Event $event)
    {
        $event->update($request->all());

        return $event;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }

    public function checkin(Request $request, $team, Event $event)
    {
        $this->authorize('checkinAll', $event);

        $event->tickets()->update([
            'checkin_at' => Carbon::now()
        ]);

        return new EventResource($event);
    }

    public function checkout(Request $request, $team, Event $event)
    {
        $this->authorize('checkinAll', $event);

        $event->tickets()->update([
            'checkin_at' => null
        ]);

        return new EventResource($event);
    }
}
