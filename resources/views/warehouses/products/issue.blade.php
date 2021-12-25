@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="row mb-3">
                    <div class="col">
                        <h1 class="h1">{{ $warehouse->name }} - výdejka</h1>
                    </div>
                </div>
                 {{ implode('', $errors->all()) }}

                <form action="/warehouse-movements" method="POST" id="warehouse_movements_form">
                    @csrf

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
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
                                        <label for="warehouse_id">Číslo skladu</label>
                                        <input readonly class="form-control" type="number" id="warehouse_id" name="warehouse_id" value="{{ $warehouse->id }}">

                                        @error('warehouse_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
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
                            <div class="form-group mt-2">
                                <div class="row">
{{--                                    <div class="col">--}}
{{--                                        <label for="amount">Množství ({{ $product->unit }})</label>--}}
{{--                                        <input class="form-control" type="number" id="amount" name="amount" value="0">--}}

{{--                                        @error('amount')--}}
{{--                                            <div class="invalid-feedback d-block">{{ $message }}</div>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
                                    <div class="col">
                                        <label for="type">Typ</label>
                                        <input readonly class="form-control" type="text" id="type" name="type" value="{{ \Illuminate\Support\Arr::last(explode('/', url()->current())) }}">

                                        @error('type')
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
