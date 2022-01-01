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
                                <th>Název (Jednotka)</th>
                                <th class="d-none d-md-table-cell">Původ</th>
                                <th class="d-none d-md-table-cell">Pořadí</th>
                                <th>Aktivní</th>
                                <th class="d-none d-md-table-cell">Obrázek</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr style="cursor: pointer;" onclick="window.location = '/products/{{$product->id}}/edit'">
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }} ({{ $product->unit }})</td>
                                    <td class="d-none d-md-table-cell">{{ $product->origin }}</td>
                                    <td class="d-none d-md-table-cell">{{ $product->order }}</td>
                                    <td>
                                        @if($product->active)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg text-success" viewBox="0 0 16 16">
                                                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x text-danger" viewBox="0 0 16 16">
                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                            </svg>
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell"><img style="max-width: 100px; max-height: 100px" src="{{ asset($product->image) }}" alt=""></td>
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
