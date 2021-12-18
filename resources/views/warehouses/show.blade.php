@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="row mb-3">
                    <div class="col">
                        <h1 class="h1">{{ $currentWarehouse->name }}</h1>
                    </div>

                    @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
                        <div class="col-4 d-flex align-items-center justify-content-between">
                            <select onchange="changeWarehouse()" class="form-control" name="warehouse" id="warehouse">
                                <option disabled selected>Přepnout sklad</option>
                                @foreach($warehouses as $warehouse)
                                    @if($warehouse->id !== $currentWarehouse->id)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="row">
                @foreach($currentWarehouse->products as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card mt-3" style="
                            background: url({{ $product->image }}) white no-repeat 50% 100%; background-size: contain;
                            border-bottom-left-radius: 0; border-bottom-right-radius: 0;
                            ">
                            <div class="card-body text-center text-white fw-bold" style="
                            min-height: 140px; font-size: 1.2rem; padding: 0; margin-top: 15px;
                            ">
                                <div style="background: rgba(100, 100, 100, 0.5); margin-top: -15px;">
                                    {{ $product->name . " ({$product->pivot->amount} $product->unit)" }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center">
                                <div class="btn-group w-100" role="group" aria-label="Product operations">
                                    <a href="/warehouses/{{$currentWarehouse->id}}/products/{{$product->id}}/receipt" type="button" class="btn btn-success" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus text-white" viewBox="0 0 16 16">
                                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                        </svg>
                                    </a>

                                    <a href="/warehouses/{{$currentWarehouse->id}}/products/{{$product->id}}/transmission" type="button" class="btn btn-warning" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left-right text-white" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5zm14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5z"/>
                                        </svg>
                                    </a>

                                    <a href="/warehouses/{{$currentWarehouse->id}}/products/{{$product->id}}/issue" type="button" class="btn btn-danger" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-dash-square text-white" viewBox="0 0 16 16">
                                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                            <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>

                @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
                    <div class="card mt-5">
                        <div class="card-body">
                            <table class="table table-responsive">
                                <thead>
                                <tr>
                                    <th>Produkt</th>
                                    <th>Počet</th>
                                    <th>Uživatel</th>
                                    <th>Typ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($currentWarehouse->warehouseMovements as $warehouseMovement)
                                    <tr>
                                        <td>{{ $warehouseMovement->product->name }}</td>
                                        <td>{{ "$warehouseMovement->amount {$warehouseMovement->product->unit}" }}</td>
                                        <td>{{ $warehouseMovement->user->name }}</td>
                                        <td>{{ $warehouseMovement->type }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
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