@extends('layouts.app')
@section('title', 'Nouvelle Assurance')

@section('content')
    <h1>Nouvelle Assurance</h1>

    <form action="{{ route('assurances.store') }}" method="POST">
        @csrf
        @include('assurances._form')
        <button type="submit" class="btn btn-primary">Créer</button>
        <a href="{{ route('assurances.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
