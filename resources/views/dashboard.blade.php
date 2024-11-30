@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Bienvenidos</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">
                        {{ Auth::user()->role->name ?? 'Sin rol asignado' }}
                    </a></li>
                    <li class="breadcrumb-item active">{{ Auth::user()->nombre }} {{ Auth::user()->apellido }} </li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-sm-0">Bienvenido, {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</h4>
                <br>
                <h4 class="mb-sm-0">Es un placer tener de vuelta.</h4>
            </div>
        </div>
    </div>
</div>
@endsection

