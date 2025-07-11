<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;

class CountryApiController extends Controller
{
    public function index()
    {
        return response()->json(Country::all());
    }

    public function store(Request $request)
    {
        $country = Country::create($request->all());
        return response()->json($country, 201);
    }

    public function show($id)
    {
        return response()->json(Country::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $country = Country::findOrFail($id);
        $country->update($request->all());
        return response()->json($country);
    }

    public function destroy($id)
    {
        Country::destroy($id);
        return response()->json(null, 204);
    }
}
