@extends('layouts.guest')

@section('content')

<div class="auth-page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mt-sm-5 mb-4 text-white-50">
                    <div>
                        <a href="index.html" class="d-inline-block auth-logo">
                            <img src="{{ asset ('assets/images/logo.png')}}" alt="" height="150">
                        </a>
                    </div>
                    <p class="mt-3 fs-15 fw-medium">Sistema de Reservas - MonkyBarber</p>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-4">

                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <h5 class="text-primary">Crear Nueva Cuenta</h5>
                            <p class="text-muted">Registrese para continuar.</p>
                        </div>
                        <div class="p-2 mt-4">
                            <form class="needs-validation" method="POST" action="{{ route('register') }}" enctype="nultipart/form-data">
                               @csrf

                                <div class="mb-3">
                                    <label for="nombre" class="form-label">{{ __('Nombre') }} <span class="text-danger">*</span></label>
                                    <input type="text" id="nombre" name="nombre" placeholder="Ingrese su nombre" value="{{ old('nombre') }}"
                                     class="form-control pe-5 @error('nombre') is-invalid @enderror" required autofocus>
                                     @error('nombre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="apellido" class="form-label">{{ __('Apellido') }} <span class="text-danger">*</span></label>
                                    <input type="text" id="apellido" name="apellido" placeholder="Ingrese su apellido" value="{{ old('apellido') }}"
                                     class="form-control pe-5 @error('apellido') is-invalid @enderror" required >
                                     @error('nombre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="telefono" class="form-label">{{ __('Teléfono') }} <span class="text-danger">*</span></label>
                                    <input type="text" id="telefono" name="telefono" placeholder="Ingrese su teléfono" value="{{ old('telefono') }}"
                                     class="form-control pe-5 @error('telefono') is-invalid @enderror" required >
                                     @error('nombre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Correo Electronico') }}<span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" placeholder="Ingrese Correo Electronico" value="{{ old('email') }}"
                                     class="form-control pe-5 @error('email') is-invalid @enderror" required >

                                     @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password">{{ __('Contraseña') }}<span class="text-danger">*</span></label>
                                        <input type="password" id="password" name="password"placeholder="Ingrese Contraseña" value="{{ old('password') }}"class="form-control pe-5 @error('passaword') is-invalid @enderror" required>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password-confirm">{{ __('Confirmar Contraseña') }}<span class="text-danger">*</span></label>
                                        <input type="password" id="password-confirm" name="password_confirmation"placeholder="Confirmar Contraseña" value="{{ old('password') }}"class="form-control" required>
                                </div>



                                <div class="mt-4">
                                    <button class="btn btn-success w-100" type="submit">{{ __('Registrate') }}</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <p class="mb-0">¿Ya tienes una cuenta ? <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-underline">Inicia Sesion </a> </p>
                </div>

            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
@endsection

