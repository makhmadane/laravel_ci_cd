@extends('layouts.app')
@section('title', 'Types d\'Assurance')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Types d'Assurance</h1>
        <a href="{{ route('types.create') }}" class="btn btn-primary">Nouveau Type</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Libellé</th>
                <th>Nb Assurances</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($types as $type)
                <tr>
                    <td>{{ $type->id }}</td>
                    <td>{{ $type->libelle }}</td>
                    <td>{{ $type->assurances_count }}</td>
                    <td>
                        <a href="{{ route('types.edit', $type) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('types.destroy', $type) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Aucun type trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $types->links() }}
@endsection
