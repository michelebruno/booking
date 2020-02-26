@extends('layouts.b2c')

@section('content')
    <div class="row">
        <div class="col">
            @guest
                Registrati
            @elseif ( /** @var  App\User  $user */ $user = request()->user() )
                {{ $user->email }}
            @else
                
            @endif
            
        </div>
        <div class="col">
            Cart
        </div>
    </div>
@endsection