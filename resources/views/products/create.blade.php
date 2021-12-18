@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="row mb-3">
                    <div class="col">
                        <h1 class="h1">Přidat produkt</h1>
                    </div>
                </div>

                <form action="/products" method="POST">
                    @csrf

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="name">Název produktu</label>
                                        <input class="form-control" type="text" id="name" name="name">

                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="unit">Jednotka</label>

                                        <select class="form-control" name="unit" id="unit">
                                            @foreach(\App\Models\Product::AVAILABLE_UNITS as $unit)
                                                <option value="{{ $unit }}">{{$unit}}</option>
                                            @endforeach
                                        </select>

                                        @error('unit')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">Uložit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
