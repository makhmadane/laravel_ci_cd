@extends('layouts.app')
@section('title', 'Détail Assurance')

@section('content')
    <h1>Détail de l'Assurance</h1>

    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Nom</dt>
                <dd class="col-sm-9">{{ $assurance->nom }}</dd>

                <dt class="col-sm-3">Numéro de Police</dt>
                <dd class="col-sm-9">{{ $assurance->numero_police }}</dd>

                <dt class="col-sm-3">Type d'Assurance</dt>
                <dd class="col-sm-9">{{ $assurance->type->libelle }}</dd>

                <dt class="col-sm-3">Montant de la Prime</dt>
                <dd class="col-sm-9">{{ number_format($assurance->montant_prime, 2, ',', ' ') }} €</dd>

                <dt class="col-sm-3">Statut</dt>
                <dd class="col-sm-9">
                    <span class="badge bg-{{ $assurance->statut === 'actif' ? 'success' : ($assurance->statut === 'inactif' ? 'warning' : 'danger') }}">
                        {{ ucfirst($assurance->statut) }}
                    </span>
                </dd>

                <dt class="col-sm-3">Créé le</dt>
                <dd class="col-sm-9">{{ $assurance->created_at->format('d/m/Y H:i') }}</dd>

                <dt class="col-sm-3">Modifié le</dt>
                <dd class="col-sm-9">{{ $assurance->updated_at->format('d/m/Y H:i') }}</dd>
            </dl>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('assurances.edit', $assurance) }}" class="btn btn-warning">Modifier</a>
        <a href="{{ route('assurances.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>
@endsection
