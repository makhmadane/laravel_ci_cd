<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssuranceRequest;
use App\Models\Assurance;
use App\Models\Type;

class AssuranceController extends Controller
{
    public function index()
    {
        $assurances = Assurance::with('type')->latest()->paginate(10);

        return view('assurances.index', compact('assurances'));
    }

    public function create()
    {
        $types = Type::all();

        return view('assurances.create', compact('types'));
    }

    public function store(AssuranceRequest $request)
    {
        Assurance::create($request->validated());

        return redirect()->route('assurances.index')
            ->with('success', 'Assurance créée avec succès.');
    }

    public function show(Assurance $assurance)
    {
        return view('assurances.show', compact('assurance'));
    }

    public function edit(Assurance $assurance)
    {
        $types = Type::all();

        return view('assurances.edit', compact('assurance', 'types'));
    }

    public function update(AssuranceRequest $request, Assurance $assurance)
    {
        $assurance->update($request->validated());

        return redirect()->route('assurances.index')
            ->with('success', 'Assurance mise à jour avec succès.');
    }

    public function destroy(Assurance $assurance)
    {
        $assurance->delete();

        return redirect()->route('assurances.index')
            ->with('success', 'Assurance supprimée avec succès.');
    }
}
