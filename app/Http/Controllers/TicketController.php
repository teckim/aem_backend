<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::with(['event', 'user'])->get();

        return $tickets;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Ticket::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Ticket::find($id);
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
        $post = Ticket::find($id);
        $post->update($request->all());

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Ticket::destroy($id);
    }

    // CHECKIN/CHECKOUT
    public function checkin(Request $request, $id) 
    {
        $post = Ticket::find($id);

        if (is_null($post->checkin_at)) {
            $post->checkin = Carbon::now();
        }

        $post->save();

        return $post;
    }

    public function checkout(Request $request, $id) 
    {
        $post = Ticket::find($id);

        if (!is_null($post->checkin_at)) {
            $post->checkin = null;
        }
        
        $post->save();
        
        return $post;
    }
}
