@extends('layouts.b2c')

@section('content')
<div class="row">
    <div class="col">
        @guest
        <h2>Registrati per procedere al pagamento.</h2>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" class="form-control" name="name" id="name" aria-describedby="helpName" placeholder="Il tuo nome" required>
                <small id="helpName" class="form-text text-muted">Conosciamoci!</small>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelper" placeholder="latuaemail@example.com" required>
                <small id="emailHelper" class="form-text text-muted">Ti verranno inviati i ticket per i tuoi prodotti.</small>
            </div>
            <div class="form-group">
                <label for="email_confirmation">conferma email</label>
                <input type="email_confirmation" class="form-control" name="email_confirmation" id="email_confirmation" placeholder="latuaemail@example.com" required>
                <small id="emailHelper" class="form-text text-muted">Ti verranno inviati i ticket per i tuoi prodotti.</small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Conferma password</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Conferma password" required>
            </div>
            <div class="form-group">
                <label for="GDPR">Trattamento dei dati...</label>
                <input type="checkbox" class="form-control" name="GDPR" id="GDPR" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrati</button>
        </form>
        @elseif ( $user = request()->user() )
        {{ $user->email }}
        <form action="{{ route('cart.checkout') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-primary">Vai al pagamento.</button>
        </form>
        @else

        @endif

    </div>
    <div class="col">
        @if ( session('status') )
        {{ session('status') }}
        @endif

        @component('cart.table')
        @endcomponent

        <form action="{{ route('cart.store') }}" method="post">
            @csrf
            <select name="prodotto">
                @foreach (App\Deal::all() as $deal)
                <option data-disponibili="{{ $deal->disponibili }}" value="{{ $deal->codice }}">{{ $deal->titolo }}</option>
                @endforeach
            </select>
            <select name="tariffa">
                @foreach ( app('VariantiTariffe') as $variante)
                <option value="{{ $variante->slug }}">{{ $variante->nome }}</option>
                @endforeach
            </select>
            <input type="number" name="quantita" min="1">

            <button type="submit" class="btn btn-primary">Invia</button>
        </form>

        <form action="{{ route('cart.destroy') }}" method="post">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-primary">Reset</button>
        </form>
    </div>
</div>
@endsection