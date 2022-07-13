<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['show']]);
    }

    public function index(Request $request) 
    {
        $files = Storage::disk('public')->files('images');
        return response()->json($files);
    }

    public function show($fileName)
    {
        return response()->json(['data' => asset('images/' . $fileName)]);
    }

    public function store(Request $request)
    {
        request()->validate([
            'image' => ['required', 'image'] 
        ]);

        $image = $request->file('image');
        $image->store('images');

        return response()->json(['message' => 'image uploaded successfully!'], 200);
    }
}
