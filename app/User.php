<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;
use Mpociot\VatCalculator\Traits\BillableWithinTheEU;

use App\Address;

class User extends Authenticatable
{
    use Notifiable, Billable, BillableWithinTheEU {
        BillableWithinTheEU::getTaxPercent insteadof Billable;
        BillableWithinTheEU::taxPercentage insteadof Billable;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'vat', 'password', 'confirmation_hash', 'trial_ends_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'trial_ends_at'
    ];

    public function address() {
        return $this->hasOne(Address::class);
    }

    public function isPastTrial() {
        return now() >= $this->trial_ends_at;
    }
}
