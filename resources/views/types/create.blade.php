@extends('layouts.app')
@section('title', 'Nouveau Type')

@section('content')
    <h1>Nouveau Type d'Assurance</h1>

    <form action="{{ route('types.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="libelle" class="form-label">Libellé</label>
            <input type="text" name="libelle" id="libelle" class="form-control @error('libelle') is-invalid @enderror"
                   value="{{ old('libelle') }}">
            @error('libelle')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
        <a href="{{ route('types.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
