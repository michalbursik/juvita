@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="row mb-3">
                    <div class="col">
                        <h1 class="h1">Produkty</h1>
                    </div>
                    <div class="col" style="text-align: right;">
                        <a href="/products/create" class="btn btn-primary">Přidat</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Jednotka</th>
                                <th>Obrázek</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr style="cursor: pointer;" onclick="window.location = '/products/{{$product->id}}/edit'">
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->unit }}</td>
                                    <td><img style="max-width: 100px" src="{{ $product->image }}" alt=""></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
