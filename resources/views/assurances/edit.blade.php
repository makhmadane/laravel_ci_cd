@extends('layouts.app')
@section('title', 'Modifier Assurance')

@section('content')
    <h1>Modifier l'Assurance : {{ $assurance->nom }}</h1>

    <form action="{{ route('assurances.update', $assurance) }}" method="POST">
        @csrf
        @method('PUT')
        @include('assurances._form')
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('assurances.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
