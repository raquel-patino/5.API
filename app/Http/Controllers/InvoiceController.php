<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{
    public function downloadInvoice($reservationId){

        return InvoiceService::generateInvoices($reservationId);
    }
}
