@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-tachometer-alt"></i> Dashboard</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Bienvenido al sistema de inscripciones. Esta es una versión preliminar.
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h5><i class="fas fa-users"></i> Participantes</h5>
                                    <h3>--</h3>
                                    <small>Total registrados</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h5><i class="fas fa-check-circle"></i> Inscritos</h5>
                                    <h3>--</h3>
                                    <small>Validados</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning">
                                <div class="card-body">
                                    <h5><i class="fas fa-clock"></i> Pendientes</h5>
                                    <h3>--</h3>
                                    <small>Por validar</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
