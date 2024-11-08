<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersonController extends Controller
{
    public function index()
    {
        return view('people.index');
    }

    public function fetchPeople()
    {
        return response()->json(Person::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('image')->store('images', 'public');

        $person = Person::create([
            'name' => $request->name,
            'age' => $request->age,
            'image_path' => $path,
        ]);

        return response()->json($person, 201);
    }

    public function update(Request $request, Person $person)
    {
        $request->validate(['name' => 'required|string', 'age' => 'required|integer']);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($person->image_path);
            $path = $request->file('image')->store('images', 'public');
            $person->image_path = $path;
        }

        $person->name = $request->name;
        $person->age = $request->age;
        $person->save();

        return response()->json($person);
    }

    public function destroy(Person $person)
    {
        Storage::disk('public')->delete($person->image_path);
        $person->delete();

        // return response()->json(null, 204);

        return response()->json(['success' => true]);
    }
}

