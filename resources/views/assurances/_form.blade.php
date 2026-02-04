<div class="mb-3">
    <label for="nom" class="form-label">Nom</label>
    <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror"
           value="{{ old('nom', $assurance->nom ?? '') }}">
    @error('nom')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="numero_police" class="form-label">Numéro de Police</label>
    <input type="text" name="numero_police" id="numero_police" class="form-control @error('numero_police') is-invalid @enderror"
           value="{{ old('numero_police', $assurance->numero_police ?? '') }}">
    @error('numero_police')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="type_id" class="form-label">Type d'Assurance</label>
    <select name="type_id" id="type_id" class="form-select @error('type_id') is-invalid @enderror">
        <option value="">-- Sélectionner un type --</option>
        @foreach($types as $type)
            <option value="{{ $type->id }}" {{ old('type_id', $assurance->type_id ?? '') == $type->id ? 'selected' : '' }}>
                {{ $type->libelle }}
            </option>
        @endforeach
    </select>
    @error('type_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="montant_prime" class="form-label">Montant de la Prime</label>
    <input type="number" name="montant_prime" id="montant_prime" step="0.01" min="0"
           class="form-control @error('montant_prime') is-invalid @enderror"
           value="{{ old('montant_prime', $assurance->montant_prime ?? '') }}">
    @error('montant_prime')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="statut" class="form-label">Statut</label>
    <select name="statut" id="statut" class="form-select @error('statut') is-invalid @enderror">
        @foreach(['actif', 'inactif', 'expire'] as $statut)
            <option value="{{ $statut }}" {{ old('statut', $assurance->statut ?? 'actif') === $statut ? 'selected' : '' }}>
                {{ ucfirst($statut) }}
            </option>
        @endforeach
    </select>
    @error('statut')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
