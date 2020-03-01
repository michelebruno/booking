<table class="table">
    <thead>
        <th></th>
        <th>Prodotto</th>
        <th>Quanità</th>
    </thead>
    <tbody>
        @if ( $carrello = session('carrello') )

            @foreach ( session('carrello') as $item )
                <tr>
                    <td>
                        <form method="POST" onclick="this.submit()" action="{{ route('cart.delete', isset($index) ? $index : $index = 0 ) }}" >
                            x
                            @csrf
                            @method('delete')
                        </form>
                    </td>
                    <td>{{ $item->descrizione }}</td>
                    <td>{{ $item->quantita }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td></td>
                <td colspan="2">Ancora nessun prodotto nel tuo carrello.</td>
            </tr>
        @endif
    </tbody>
</table>