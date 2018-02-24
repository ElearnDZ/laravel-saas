<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpociot\VatCalculator\VatCalculator;

class VatServiceController extends Controller
{
    public function validateVatNumber(VatCalculator $calculator) {
        try {
            $vatIsValid =  $calculator->isValidVATNumber(request('vat'));

            return [
                'error' => false,
                'valid' => $vatIsValid
            ];
        } catch( VATCheckUnavailableException $e ){
            return [
                'error' => 'Service is unavailable right now. Please try again later.'
            ];
        }
    }
}
