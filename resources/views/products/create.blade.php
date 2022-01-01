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
                                    <div class="col-12 col-md mb-2">
                                        <label for="name">Název produktu<span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}">

                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md mb-2">
                                        <label for="origin">Původ</label>
                                        <input class="form-control" type="text" id="origin" name="origin" value="{{ old('origin') }}">

                                        @error('origin')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md mb-2">
                                        <label for="unit">Jednotka<span class="text-danger">*</span></label>

                                        <select class="form-control" name="unit" id="unit">
                                            @foreach(\App\Models\Product::AVAILABLE_UNITS as $unit)
                                                @if(old('unit') === $unit)
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
                                    <div class="col-12 col-md mb-2">
                                        <label for="order">Pořadí<span class="text-danger">*</span></label>
                                        <input class="form-control" type="number" id="order" name="order" value="{{ $productOrder }}">

                                        @error('order')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md mb-2">
                                        <label class="d-none d-md-block" for="">&nbsp;</label>

                                        <div class="form-control bg-transparent border-0">
                                            <input class="form-check-input" type="checkbox" id="active"  name="active" checked>
                                            <label class="form-check-label" for="active">Aktivní</label>
                                        </div>
                                        @error('active')
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
