<?php

use App\Models\Location;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('locations/print-qr/{location}', function (Location $location) {
    $pdf = Pdf::loadView('filament.print_qr', [
        'location' => $location,
    ]);
    return $pdf->stream('label.pdf');
})->name("location.print");
