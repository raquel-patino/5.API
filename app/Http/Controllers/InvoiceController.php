<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{
/**
 * @OA\Get(
 *     path="/reservations/{id}/invoices",
 *     summary="Download invoice for a reservation",
 *     tags={"Invoices"},
 *     security={{"Bearer":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the reservation",
 *         required=true,
 *         @OA\Schema(type="integer", example=8)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Invoice generated successfully",
 *         @OA\MediaType(
 *             mediaType="application/pdf"
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Reservation not found or cannot generate invoice"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

    public function downloadInvoice($reservationId){

        return InvoiceService::generateInvoices($reservationId);
    }
}
