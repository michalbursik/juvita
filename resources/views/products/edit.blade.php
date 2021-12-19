@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="row mb-3">
                    <div class="col">
                        <h1 class="h1">Změnit produkt - {{ $product->name }}</h1>
                    </div>
                </div>

                <form action="/products/{{$product->id}}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="name">Název produktu</label>
                                        <input class="form-control" type="text" id="name" name="name" value="{{ $product->name }}">

                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="unit">Jednotka</label>
                                        <select class="form-control" name="unit" id="unit">
                                            @foreach(\App\Models\Product::AVAILABLE_UNITS as $unit)
                                                @if($product->unit === $unit)
                                                    <option selected value="{{ $unit }}">{{$unit}}</option>
                                                @else
                                                    <option value="{{ $unit }}">{{$unit}}</option>
                                                @endif
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
