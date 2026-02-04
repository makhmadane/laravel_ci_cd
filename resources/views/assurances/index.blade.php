@extends('layouts.app')
@section('title', 'Assurances')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Liste des Assurances</h1>
        <a href="{{ route('assurances.create') }}" class="btn btn-primary">Nouvelle Assurance</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>N° Police</th>
                <th>Type</th>
                <th>Montant Prime</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assurances as $assurance)
                <tr>
                    <td>{{ $assurance->id }}</td>
                    <td>{{ $assurance->nom }}</td>
                    <td>{{ $assurance->numero_police }}</td>
                    <td>{{ $assurance->type->libelle }}</td>
                    <td>{{ number_format($assurance->montant_prime, 2, ',', ' ') }} €</td>
                    <td>
                        <span class="badge bg-{{ $assurance->statut === 'actif' ? 'success' : ($assurance->statut === 'inactif' ? 'warning' : 'danger') }}">
                            {{ ucfirst($assurance->statut) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('assurances.show', $assurance) }}" class="btn btn-sm btn-info">Voir</a>
                        <a href="{{ route('assurances.edit', $assurance) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('assurances.destroy', $assurance) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Aucune assurance trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $assurances->links() }}
@endsection
