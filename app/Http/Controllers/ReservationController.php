<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ReservationDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class ReservationController extends Controller
{

    public function index(){

        $reservations = Reservation::with(['user', 'consultant'])->get();
        return view('reservations.index', compact('reservations'));
    }

    public function indexcliente(){
        $userId = Auth::user()->id;

        $reservations = Reservation::where('user_id', $userId)->get();
        return view('cliente.index', compact('reservations'));
    }

    public function create(){
        $users = User::where('rol_id', 3)->whereNull('deleted_at')->get();
        $consultants = User::where('rol_id', 2)->whereNull('deleted_at')->get();

        return view('reservations.create', compact('users', 'consultants'));
    }

    public function createCliente(){

        $consultants = User::where('rol_id', 2)->whereNull('deleted_at')->get();

        return view('cliente.reserva', compact('consultants'));
    }

    public function store(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'consultant_id' => 'required|exists:users,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i|after_or_equal:09:00|before_or_equal:15:00',
            'end_time' => 'required|date_format:H:i|before_or_equal:15:00',
            'reservation_status' => 'required|in:pendiente,confirmada,cancelada',
            'payment_status' => 'required|in:pendiente,pagado,fallido',
            'total_amount' => 'required|numeric|min:0',
        ]);
        // Creación de la reserva
        $reservation = Reservation::create([

            'user_id' => $request->user_id,
            'consultant_id' => $request->consultant_id,
            'reservation_date' => $request->reservation_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reservation_status' => $request->reservation_status,
            'payment_status' => $request->payment_status,
            'total_amount' => $request->total_amount,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reserva creada correctamente');
    }

    public function edit(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->start_time = Carbon::parse($reservation->start_time)->format('H:i');
        $reservation->end_time = Carbon::parse($reservation->end_time)->format('H:i');

        $users = User::where('rol_id', 3)->whereNull('deleted_at')->get();
        $consultants = User::where('rol_id', 2)->whereNull('deleted_at')->get();

        return view('reservations.edit', compact('reservation', 'users', 'consultants'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'consultant_id' => 'required|exists:users,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i|after_or_equal:09:00|before_or_equal:15:00',
            'end_time' => 'required|date_format:H:i|before_or_equal:15:00',
            'reservation_status' => 'required|in:pendiente,confirmada,cancelada',
            'payment_status' => 'required|in:pendiente,pagado,fallido',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());

        return redirect()->route('reservations.index')->with('success', 'Reserva actualizada correctamente');
    }

    public function cancel(Request $request){
        // Validación de los datos
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'cancellation_reason' => 'required|string',
        ]);

        $reservation = Reservation::findOrFail($request->reservation_id);
        $reservation->reservation_status = 'cancelada'; // Cambia el estado a 'cancelada'
        $reservation->cancellation_reason = $request->cancellation_reason;
        $reservation->save();

        return response()->json([
            'success' => true,
            'message' => 'La reserva ha sido cancelada exitosamente',
        ]);
    }

    public function getAllReservations(){

        $reservations = Reservation::all();
        $events = [];
        foreach ($reservations as $reservation) {

            $color = '#28a745';
            $bordercolor = '#28a745';

            if ($reservation->reservation_status === 'pendiente') {
                $color = '#ffc107';
                $bordercolor = '#ffc107';
            } elseif ($reservation->reservation_status === 'cancelada') {
                $color = '#dc3545';
                $bordercolor = '#dc3545';
            }

            $events[] = [
                'title' => 'Reserva de ' . $reservation->user->nombre . ' ' . $reservation->user->apellido . ' con ' . $reservation->consultant->nombre . ' ' . $reservation->consultant->apellido,
                'start' => $reservation->reservation_date . 'T' . $reservation->start_time,
                'end' => $reservation->reservation_date . 'T' . $reservation->end_time,
                'backgroundColor' => $color,
                'borderColor' => $bordercolor

            ];
        }

        return response()->json($events);
    }

    public function getReservationsAsesor(){

        $consultantId = Auth::user()->id;

        $reservations = Reservation::where('consultant_id', $consultantId)->get();

        $events = [];
        foreach ($reservations as $reservation) {

            $color = '#28a745';
            $bordercolor = '#28a745';

            if ($reservation->reservation_status === 'pendiente') {
                $color = '#ffc107';
                $bordercolor = '#ffc107';
            } elseif ($reservation->reservation_status === 'cancelada') {
                $color = '#dc3545';
                $bordercolor = '#dc3545';
            }

            $events[] = [
                'title' => 'Reserva con ' . $reservation->consultant->nombre . ' ' . $reservation->consultant->apellido . ' con ' . $reservation->consultant->nombre . ' ' . $reservation->consultant->apellido,
                'start' => $reservation->reservation_date . 'T' . $reservation->start_time,
                'end' => $reservation->reservation_date . 'T' . $reservation->end_time,
                'backgroundColor' => $color,
                'borderColor' => $bordercolor

            ];
        }

        return response()->json($events);
    }

    public function getReservationsCliente(){

        $userId = Auth::user()->id;

        $reservations = Reservation::where('user_id', $userId)->get();

        $events = [];
        foreach ($reservations as $reservation) {

            $color = '#28a745';
            $bordercolor = '#28a745';

            if ($reservation->reservation_status === 'pendiente') {
                $color = '#ffc107';
                $bordercolor = '#ffc107';
            } elseif ($reservation->reservation_status === 'cancelada') {
                $color = '#dc3545';
                $bordercolor = '#dc3545';
            }

            $events[] = [
                'title' => 'Reserva con ' . $reservation->consultant->nombre . ' ' . $reservation->consultant->apellido . ' con ' . $reservation->consultant->nombre . ' ' . $reservation->consultant->apellido,
                'start' => $reservation->reservation_date . 'T' . $reservation->start_time,
                'end' => $reservation->reservation_date . 'T' . $reservation->end_time,
                'backgroundColor' => $color,
                'borderColor' => $bordercolor

            ];
        }

        return response()->json($events);
    }

    public function completePayment(Request $request){
        $request->validate([
            'orderID' => 'required',
            'details' => 'required',
            'user_id' => 'required|exists:users,id',
            'consultant_id' => 'required|exists:users,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i|after_or_equal:09:00|before_or_equal:15:00',
            'end_time' => 'required|date_format:H:i|before_or_equal:15:00',
            'total_amount' => 'required|numeric|min:0',

        ]);

        $details =$request->details;
        $payment_status = $details['status'];

        if($payment_status === 'COMPLETED'){

            $reservation = Reservation::create([
                'user_id' => $request->user_id,
                'consultant_id' => $request->consultant_id,
                'reservation_date' => $request->reservation_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'reservation_status' => 'confirmada',
                'payment_status' => 'pagado',
                'total_amount' => $request->total_amount,
            ]);

            $transaction_id = $details['id'] ?? null;
            $payer_id = $details['payer']['payer_id'] ?? null;
            $payer_email = $details['payer']['email_address'] ?? null;
            $amount = $details['purchase_units'][0]['amount']['value'] ?? null;

            ReservationDetail::create([
                'reservation_id' => $reservation->id,
                'transaction_id' => $transaction_id,
                'payer_id' => $payer_id,
                'payer_email' => $payer_email,
                'payment_status' => $payment_status,
                'amount' => $amount,
                'response_json' => json_encode($details),
            ]);

            return response()->json(['success' => true]);

        }else {
            return response()->json(['error' => 'Pago no completado'], 400);
        }
    }

    public function showPayments(){
        $payments = ReservationDetail::with(['reservation.user','reservation.consultant'])->get();
        return view('reservations.pagos', compact('payments'));
    }

    public function showClientPayments(){
        $userId = Auth::id();

        $payments = ReservationDetail::whereHas('reservation',function($query) use ($userId){
            $query->where('user_id',$userId);
        })->get();
        return view('cliente.pagos', compact('payments'));
    }
}
