@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="row mb-3">
                    <div class="col">
                        <h1 class="h1">{{ $warehouseFrom->name }} - převodka</h1>
                    </div>
                </div>

                <form action="/warehouse-movements/transmission" method="POST" id="warehouse_movements_form">
                    @csrf


                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <label for="issue_warehouse_id">Sklad - Výdej</label>
                                    <select class="form-control" name="issue_warehouse_id" id="issue_warehouse_id">
                                        @foreach($warehouses as $warehouse)
                                            {{-- Currently there is only one! --}}
                                            @if($warehouse->type === \App\Models\Warehouse::TYPE_MAIN)
                                                <option selected value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @else
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    @error('issue_warehouse_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="receipt_warehouse_id">Sklad - příjem</label>
                                    <select @if($user->role === \App\Models\User::ROLE_EMPLOYEE) {{ 'readonly' }} @endif class="form-control" name="receipt_warehouse_id" id="receipt_warehouse_id">
                                        @foreach($warehouses as $warehouse)
                                            @if($user->role === \App\Models\User::ROLE_EMPLOYEE)
                                                @if($warehouse->id === $user->warehouse_id)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                @endif
                                            @else
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>


                                    @error('receipt_warehouse_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col">
                                    <label for="amount">Množství ({{ $product->unit }})</label>
                                    <input readonly class="form-control" type="text" id="amount" name="amount" value="0">

                                    @error('amount')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="product">Produkt</label>
                                    <input class="form-control" readonly type="text" value="{{ $product->name }}">
                                </div>
                            </div>

                            <x-numeric-pad></x-numeric-pad>
                        </div>
                    </div>

                    <div class="card mt-3 d-none">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="product_id">Číslo produktu</label>
                                        <input readonly class="form-control" type="number" id="product_id" name="product_id" value="{{ $product->id }}">

                                        @error('product_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="user_id">Číslo uživatele</label>
                                        <input readonly class="form-control" type="number" id="user_id" name="user_id" value="{{ $user->id }}">

                                        @error('user_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2 d-none">Uložit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
