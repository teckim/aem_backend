<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Event;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items_per_page = request()->query('itemsPerPage', 10);
        $sort_by = request()->query('sortBy', [null])[0];
        $desc = (request()->query('sortDesc', ['false'])[0] === 'true');
        $search = request()->query('search', '');

        if ($sort_by)
            $locations = Location::orderBy($sort_by, $desc ? 'desc' : 'asc')
                ->where('name', 'like', '%' . $search . '%')
                ->paginate($items_per_page);
        else $locations = Location::where('name', 'like', '%' . $search . '%')
            ->paginate($items_per_page);

        return $locations;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required|string|between:2,190',
            'address' => 'required|string|between:2,190',
            'url' => 'string|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        return Location::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Location::find($id);
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
        $post = Location::find($id);
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
        $hasEvents = Event::where('location_id', $id)->count();
        if ($hasEvents) {
            return response()->json(['message' => 'Location has events related to.'], 500);
        }

        return Location::destroy($id);
    }
}
