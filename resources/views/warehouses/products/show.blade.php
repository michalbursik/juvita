@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="row mb-3">
                    <div class="col-12 col-sm">
                        <h1 class="h1">{{ $currentWarehouse->name }} - {{ $product->name }}</h1>
                    </div>
                </div>

                @if($product->priceLevels->isNotEmpty())
                <div class="card my-3">
                    <div class="card-header">{{ $product->name }} - Cenové hladiny </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col"><strong>Počet</strong></div>
                            <div class="col"><strong>Cena</strong></div>
                        </div>
                        @foreach($product->priceLevels as $priceLevel)
                            <div class="row">
                                <div class="col">
                                    {{ $priceLevel->amount }} {{ $product->unit }}
                                </div>
                                <div class="col">
                                    {{ $priceLevel->price }} Kč
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Produkt</th>
                                <th>Typ</th>
                                <th>Počet</th>
                                <th>Cena</th>
                                <th>Uživatel</th>
                                <th>Vytvořeno</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($warehouseMovements as $warehouseMovement)
                                <tr class="{{ $warehouseMovement->type }}-color">
                                    <td>{{ $warehouseMovement->product->name }}</td>
                                    <td>{{ trans('global.' .$warehouseMovement->type) }}</td>
                                    <td>{!! "$warehouseMovement->amount&nbsp;{$warehouseMovement->product->unit}" !!}</td>
                                    <td>{{ $warehouseMovement->price }}&nbsp;Kč</td>
                                    <td>{{ $warehouseMovement->user->name }}</td>
                                    <td>{{ $warehouseMovement->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script lang="js">
        function changeWarehouse() {
            let el = document.getElementById('warehouse');

            let warehouse_id = el.value;

            window.location = '/warehouses/' + warehouse_id
        }
    </script>
@endsection
