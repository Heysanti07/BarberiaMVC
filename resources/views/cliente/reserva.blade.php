@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Nueva Reserva</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reserva</a></li>
                        <li class="breadcrumb-item active">Nueva Reserva</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Crear Nueva Reserva</h4>
                </div>
                <div class="card-body">
                    <form class="row gy-1" id="reservationForm">
                        @csrf

                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label for="user" class="form-label">{{ __('Usuario') }}</label>
                                <input type="text" class="form-control @error('user_id') is-invalid @enderror"
                                    id="user" name="user_id"
                                    value="{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}" readonly>
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" id="user_id">
                            </div>
                        </div>

                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label for="consultant_id" class="form-label">{{ __('Consultor') }}</label>
                                <select class="form-select @error('consultant_id') is-invalid @enderror" id="consultant_id"
                                    name="consultant_id" required>
                                    <option value="">Seleccionar Consultor</option>
                                    @foreach ($consultants as $consultant)
                                        <option value="{{ $consultant->id }}">{{ $consultant->nombre }}
                                            {{ $consultant->apellido }}</option>
                                    @endforeach
                                </select>
                                @error('consultant_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label for="reservation_date" class="form-label">{{ __('Fecha de Reserva') }}</label>
                                <input type="date" class="form-control @error('reservation_date') is-invalid @enderror"
                                    id="reservation_date" name="reservation_date" value="{{ old('reservation_date') }}"
                                    required>
                                @error('reservation_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label for="start_time" class="form-label">{{ __('Hora de Inicio') }}</label>
                                <select class="form-select @error('start_time') is-invalid @enderror" id="start_time"
                                    name="start_time" required>
                                    <option value="">Seleccionar una hora</option>
                                    <option value="09:00">09:00</option>
                                    <option value="10:00">10:00</option>
                                    <option value="11:00">11:00</option>
                                    <option value="12:00">12:00</option>
                                    <option value="13:00">13:00</option>
                                    <option value="14:00">14:00</option>
                                    <option value="15:00">15:00</option>
                                    <option value="16:00">16:00</option>
                                </select>
                                @error('start_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label for="end_time" class="form-label">{{ __('Hora Fin') }}</label>
                                <input type="text" class="form-control @error('end_time') is-invalid @enderror"
                                    id="end_time" name="end_time" readonly>
                                @error('end_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label for="reservation_status" class="form-label">{{ __('Estado de la Reserva') }}</label>
                                <select class="form-select @error('reservation_status') is-invalid @enderror"
                                    id="reservation_status" name="reservation_status" required>
                                    <option value="">Seleccionar un estado</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="confirmada">Confirmada</option>
                                </select>
                                @error('reservation_status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label for="total_amount" class="form-label">{{ __('Total a pagar (MXN)') }}</label>
                                <input type="text" class="form-control @error('total_amount') is-invalid @enderror"
                                    id="total_amount" name="total_amount" readonly>
                                @error('total_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label for="payment_status" class="form-label">{{ __('Metodo de Pago') }}</label>
                                <div id="paypal-button-container"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script
        src="https://www.paypal.com/sdk/js?client-id=AYzyl3blnmqFvkx0_hUSOWz-6vk6vsmqNl5DtFOYPG3uHDw3RNgIn8wE8UxpcHqnArWnf9wBg30FCmYR&currency=MXN">
    </script>
    <script>
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('reservation_date').setAttribute('min', today);

        const pricePerHour = 50; // Define el precio por hora

        document.getElementById('start_time').addEventListener('change', function() {
            const startTime = this.value;

            if (startTime) {
                // Convertir la hora de inicio a un objeto Date
                const startDate = new Date(`1970-01-01T${startTime}:00`);
                // Añadir una hora
                startDate.setHours(startDate.getHours() + 1);
                // Formatear la nueva hora como HH:MM
                const endTime = startDate.toTimeString().slice(0, 5);
                // Establecer el valor de end_time
                document.getElementById('end_time').value = endTime;

                // Calcular el total (en este caso siempre será 1 hora, pero puedes ajustar según el tiempo)
                const total = pricePerHour; // Siempre será 1 hora, así que multiplica por el precio
                document.getElementById('total_amount').value = total.toFixed(2); // Actualizar el total
            } else {
                // Limpiar el campo end_time si no se selecciona una hora
                document.getElementById('end_time').value = "";
                document.getElementById('total_amount').value = "";
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            paypal.Buttons({
                createOrder: function(data, actions) {
                    var consultantId = document.getElementById('consultant_id').value;
                    var reservationDate = document.getElementById('reservation_date').value;
                    var startTime = document.getElementById('start_time').value;
                    var totalAmount = document.getElementById('total_amount').value;

                    if (!consultantId || !reservationDate || !startTime || !totalAmount) {

                        Swal.fire({
                            icon: 'warning',
                            title: 'Campos Incompletos',
                            text: 'Por Favor, completa todos los campos obligatorio',
                        });
                        return false;
                    }

                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: totalAmount
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        return fetch('/paypal', {
                            method: 'post',
                            headers: {
                                'content-type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                orderID: data.orderID,
                                details: details,
                                user_id: {{ auth()->user()->id }},
                                consultant_id: document.getElementById(
                                    'consultant_id').value,
                                reservation_date: document.getElementById(
                                    'reservation_date').value,
                                start_time: document.getElementById(
                                    'start_time').value,
                                end_time: document.getElementById('end_time')
                                    .value,
                                total_amount: document.getElementById(
                                    'total_amount').value,

                            })
                        }).then(function(response) {
                            if (response.ok) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pago Completado',
                                    text: 'Pago completado y reserva creada correctamente',
                                }).then(function(){
                                    window.location.href = '/cliente/reservas';
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Erro al procesar el pago',
                                })
                            }
                        })
                    });
                }
            }).render('#paypal-button-container');
        });
    </script>
@endpush
