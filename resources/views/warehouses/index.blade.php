@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="row mb-3">
                    <div class="col">
                        <h1 class="h1">Sklady</h1>
                    </div>
                </div>

                <div class="row">
                    @foreach($warehouses as $warehouse)
                        <div class="col-6 col-md-4 col-lg-3 mt-3">
                            <a href="/warehouses/{{$warehouse->id}}" class="fw-bold text-decoration-none text-black">
                                <div class="card" style="min-height: 100px;">
                                    <div class="card-body" style="display:flex; align-items: center; justify-content: center;">
                                        <div class="text-center font-weight-bold">
                                            {{ $warehouse->name }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
