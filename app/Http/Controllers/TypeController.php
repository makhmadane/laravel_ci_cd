<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeRequest;
use App\Models\Type;

class TypeController extends Controller
{
    public function index()
    {

        $types = Type::withCount('assurances')->latest()->paginate(10);

        return view('types.index', compact('types'));
    }

    public function create()
    {
        return view('types.create');
    }

    public function store(TypeRequest $request)
    {
        Type::create($request->validated());

        return redirect()->route('types.index')
            ->with('success', 'Type créé avec succès.');
    }

    public function edit(Type $type)
    {
        return view('types.edit', compact('type'));
    }

    public function update(TypeRequest $request, Type $type)
    {
        $type->update($request->validated());

        return redirect()->route('types.index')
            ->with('success', 'Type mis à jour avec succès.');
    }

    public function destroy(Type $type)
    {
        $type->delete();

        return redirect()->route('types.index')
            ->with('success', 'Type supprimé avec succès.');
    }
}
