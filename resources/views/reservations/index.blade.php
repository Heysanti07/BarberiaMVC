@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Lista de Reservas</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reservas</a></li>
                        <li class="breadcrumb-item active">lista de Reservas</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Reservas</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('reservations.create') }}"
                        class="card-body btn-primary waves-effect waves-light">Nueva Reserva</a>
                    <br>
                    <br>
                    <table id="reservationTable"
                        class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Consultor</th>
                                <th>Fecha</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Estado del Pago</th>
                                <th>Estado de la Reserva</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->user->nombre }} {{ $reservation->user->apellido }}</td>
                                    <td>{{ $reservation->consultant->nombre }} {{ $reservation->consultant->apellido }}</td>
                                    <td>{{ $reservation->reservation_date }}</td>
                                    <td>{{ $reservation->start_time }}</td>
                                    <td>{{ $reservation->end_time }}</td>
                                    <td>{{ $reservation->payment_status }}</td>
                                    <td>
                                        @if($reservation->reservation_status == 'cancelada')
                                            <span class="badge bg-danger">cancelada</span>
                                        @elseif ($reservation->reservation_status == 'confirmada')
                                            <span class="badge bg-success">confirmada</span>
                                        @else
                                            <span class="badge bg-warning">pendiente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($reservation->reservation_status == 'cancelada')
                                            <button class="btn btn-warning btn-sm" disabled>Editar</button>
                                            <button class="btn btn-danger btn-sm" disabled>Cancelar</button>
                                        @else
                                            <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                            <button type="button" class="btn btn-danger btn-sm btn-cancel" data-id="{{ $reservation->id }}">Cancelar</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#reservationTable').DataTable();
        });

        $('.btn-cancel').on('click', function(e) {
            e.preventDefault();
            var reservationId = $(this).data('id');

            Swal.fire({
            title: "Cancelar Reserva", // Título del modal
            icon: "warning", // Ícono de advertencia
            text: "Por favor, ingresa la razón de la cancelación:", // Texto del modal
            input: 'textarea', // Tipo de input para ingresar la razón
            inputPlaceholder: 'Escribe la razón de la cancelación aquí...', // Placeholder para el input
            showCancelButton: true, // Mostrar botón de cancelar
            confirmButtonText: "Cancelar Reserva", // Texto del botón de confirmación
            cancelButtonText: 'Cerrar', // Texto del botón de cerrar
            preConfirm: (cancellationReason) => {
                // Validación: Si no se ingresa una razón, mostrar un mensaje de error
                if (!cancellationReason) {
                    swal.showValidationMessage('Es necesario ingresar una razón');
                    return false;
                } else {
                    // Promesa para realizar la solicitud AJAX
                    return new Promise((resolve) => {
                        $.ajax({
                            url: "{{ route('reservations.cancel') }}", // Ruta para cancelar la reserva
                            method: "POST", // Método POST para enviar la solicitud
                            data: {
                                _token: "{{ csrf_token() }}", // Token CSRF para la seguridad de Laravel
                                reservation_id: reservationId, // ID de la reserva
                                cancellation_reason: cancellationReason, // Razón de la cancelación
                            },
                            success: function(response) {
                                // Si la solicitud es exitosa y la reserva fue cancelada
                                if (response.success) {
                                    Swal.fire({
                                        title: "Reserva cancelada", // Título del mensaje de éxito
                                        text: response.message, // Mensaje de éxito
                                        icon: "success" // Ícono de éxito
                                    }).then(() => {
                                        location.reload(); // Recargar la página
                                    });
                                } else {
                                    // Si hay un error en la solicitud
                                    Swal.fire({
                                        title: "Error", // Título del mensaje de error
                                        text: response.message, // Mensaje de error
                                        icon: "error" // Ícono de error
                                    });
                                }
                            }
                        });
                    });
                }
            }
        });
        });
    </script>
    @if (session('success'))
        <script>
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                },
            }).showToast();
        </script>
    @endif

@endpush
