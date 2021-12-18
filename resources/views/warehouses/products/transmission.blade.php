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
                                    <input readonly class="form-control" type="number" id="amount" name="amount" value="0">

                                    @error('amount')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="product">Produkt</label>
                                    <input class="form-control" readonly type="text" value="{{ $product->name }}">
                                </div>
                            </div>

                            <div class="row numeric-pad">
                                <div class="col">
                                    <div class="pad" onclick="writeDown('1')">1</div>
                                    <div class="pad" onclick="writeDown('4')">4</div>
                                    <div class="pad" onclick="writeDown('7')">7</div>
                                    <div class="pad text-danger" onclick="removeLast()">
                                        &nbsp;
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-backspace" viewBox="0 0 16 16">
                                            <path d="M5.83 5.146a.5.5 0 0 0 0 .708L7.975 8l-2.147 2.146a.5.5 0 0 0 .707.708l2.147-2.147 2.146 2.147a.5.5 0 0 0 .707-.708L9.39 8l2.146-2.146a.5.5 0 0 0-.707-.708L8.683 7.293 6.536 5.146a.5.5 0 0 0-.707 0z"/>
                                            <path d="M13.683 1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-7.08a2 2 0 0 1-1.519-.698L.241 8.65a1 1 0 0 1 0-1.302L5.084 1.7A2 2 0 0 1 6.603 1h7.08zm-7.08 1a1 1 0 0 0-.76.35L1 8l4.844 5.65a1 1 0 0 0 .759.35h7.08a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1h-7.08z"/>
                                        </svg>
                                        &nbsp;
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="pad" onclick="writeDown('2')">2</div>
                                    <div class="pad" onclick="writeDown('5')">5</div>
                                    <div class="pad" onclick="writeDown('8')">8</div>
                                    <div class="pad" onclick="writeDown('0')">0</div>
                                </div>
                                <div class="col">
                                    <div class="pad" onclick="writeDown('3')">3</div>
                                    <div class="pad" onclick="writeDown('6')">6</div>
                                    <div class="pad" onclick="writeDown('9')">9</div>
                                    <div class="pad text-success" onclick="submitForm()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
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

    <script lang="js">
        function writeDown(number) {
            let input = document.getElementById('amount');

            if (parseInt(input.value) === 0) {
                input.value = number;
            } else {
                input.value += number;
            }
        }

        function removeLast() {
            let input = document.getElementById('amount');

            if (input.value.length > 1) {
                input.value = input.value.substr(0, input.value.length - 1);
            } else {
                input.value = 0;
            }
        }

        function submitForm() {
            let form = document.getElementById('warehouse_movements_form');

            form.submit();
        }
    </script>
@endsection
