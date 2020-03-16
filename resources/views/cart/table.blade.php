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
                <form method="POST" onclick="this.submit()" action="{{ route('cart.delete', $loop->index ) }}">
                    x
                    @csrf
                    @method('delete')
                </form>
            </td>
            <td>{{ $item->descrizione }}</td>
            <td @error($item->codice) style="color:red;" @enderror >{{ $item->quantita }}</td>
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