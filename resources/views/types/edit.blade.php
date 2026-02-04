@extends('layouts.app')
@section('title', 'Modifier Type')

@section('content')
    <h1>Modifier le Type : {{ $type->libelle }}</h1>

    <form action="{{ route('types.update', $type) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="libelle" class="form-label">Libellé</label>
            <input type="text" name="libelle" id="libelle" class="form-control @error('libelle') is-invalid @enderror"
                   value="{{ old('libelle', $type->libelle) }}">
            @error('libelle')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('types.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
