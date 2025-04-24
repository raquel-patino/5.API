<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Reserva;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class InvoiceService{

public static function generateInvoices( $reservationId){

    $reservation = Reservation::with(['user', 'hotel', 'room'])->findOrFail($reservationId);
    Gate::authorize('view', $reservation);

    $checkIn= Carbon::parse($reservation->check_in);
    $checkOut= Carbon::parse($reservation->check_out);
    $days= $checkIn->diffInDays($checkOut);


    $pdf= Pdf::loadView('pdf.invoice', compact('reservation', 'days'));
    $name= 'invoice_reservation_'. Str::slug($reservation->user->surname). '.pdf';

    return $pdf->download($name); 
    }
    
}


?>