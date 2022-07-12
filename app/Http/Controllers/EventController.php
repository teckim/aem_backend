<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;

use App\Notifications\NewEvent;
use Illuminate\Support\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;

class EventController extends Controller
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
    public function index()
    {
        // $this->authorize('viewAny', Event::class);

        $items_per_page = request()->query('itemsPerPage', 10);
        $sort_by = request()->query('sortBy', [null])[0];
        $desc = (request()->query('sortDesc', ['false'])[0] === 'true');
        $search = request()->query('search', '');
        $state = request()->query('state', 'all');

        $event = Event::with(['category', 'location']);
        if ($sort_by) $events = $event->orderBy($sort_by, $desc ? 'desc' : 'asc');
        if ($state != 'all') {
            if ($state == 'soon') {
                $event = $event->where('start_at', '>', Carbon::now());
            } else if ($state == 'past') {
                $event = $event->where('end_at', '<', Carbon::now());
            } else {
                $event = $event
                    ->where('start_at', '<=', Carbon::now())
                    ->where('end_at', '>=', Carbon::now());
            }
        }
        $events = $event->where('title', 'like', '%' . $search . '%')
            ->paginate($items_per_page);

        return new EventCollection($events);
        
        // return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $request->merge([
            'id' => generateID(Event::class)
        ]);
        $data = $request->all();
        $data['start_at'] = Carbon::parse($data['start_at']);
        $data['end_at'] = Carbon::parse($data['end_at']);
        // if (isset($data['publish_at'])) {
        //     $data['publish_at'] = Carbon::parse($data['publish_at']);
        // }
        // if (isset($data['unpublish_at'])) {
        //     $data['unpublish_at'] = Carbon::parse($data['unpublish_at']);
        // }

        $event = Event::create($data);
        $users = User::all();

        foreach ($users as $user) {
            Notification::route('mail', $user->email)
                ->notify(new NewEvent($event, $user));
        }
        return response()->json($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $event = $event->load(['category', 'location', 'team']);
        // $event = Event::with(['category', 'location', 'tickets' => function ($query) {
        //     $query->where('checkin_at', null);
        // }])->find($id);

        return new EventResource($event);
        // return response()->json($event->with(['category', 'location', 'team'])->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, Event $event)
    {
        // return auth()->user()->roles()->with('permissions')->get()
        //     ->pluck('permissions')
        //     ->flatten()
        //     ->pluck('name')
        //     ->toArray();
        // $event = Event::find($id);
        // $this->authorize('update', $event);
        $event->update($request->all());
        return $event;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Event::destroy($id);
    }
}
