@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="row mb-3">
                    <div class="col">
                        <h1 class="h1">{{ $warehouse->name }} - příjemka</h1>
                    </div>
                </div>

                <form action="/warehouse-movements/receipt" method="POST" id="warehouse_movements_form">
                    @csrf

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <label for="amount">Množství ({{ $product->unit }})</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        id="amount"
                                        name="amount"
                                        value="0"
                                        readonly
                                    >

                                    @error('amount')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="price">Cena</label>
                                    <input
                                        class="form-control"
                                        type="number"
                                        id="price"
                                        name="price"
                                        value="{{ $product->pivot->price }}"
                                        readonly
                                    >

                                    @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="product">Produkt</label>
                                    <input class="form-control" readonly type="text" value="{{ $product->name }}">
                                </div>
                            </div>

                            <script type="text/javascript">
                                function setCurrentInput(value) {
                                    let currentInput = getCurrentInput();

                                    if (currentInput) {
                                        document.getElementById(currentInput).classList.remove('border-success');
                                    }

                                    window.localStorage.setItem('currentInput', value);

                                    let el = document.getElementById(value);
                                    el.classList.add('border-success');
                                }

                                function getCurrentInput() {
                                    return window.localStorage.getItem('currentInput');
                                }

                                setCurrentInput('amount');

                                let items = ['amount', 'price'];
                                items.forEach(function (value) {
                                    let amountInput = document.getElementById(value);

                                    amountInput.addEventListener('click', function () {
                                        let currentInput = getCurrentInput();

                                        if (currentInput) {
                                            document.getElementById(currentInput).classList.remove('border-success');
                                        }

                                        window.localStorage.setItem('currentInput', value);

                                        let el = document.getElementById(value);
                                        el.classList.add('border-success');

                                        if (el.value === '0.00') {
                                            el.value = 0;
                                        }
                                    });
                                });
                            </script>

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
