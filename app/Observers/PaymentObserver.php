<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    /**
     * Handle the payment "creating" event.
     *
     * @return void
     */
    public function creating(Payment $payment)
    {
        $payment->code = $payment->generatePaymentCode();
    }

    /**
     * Handle the Payment "created" event.
     *
     * @return void
     */
    public function created(Payment $payment)
    {
        //
    }

    /**
     * Handle the Payment "updated" event.
     *
     * @return void
     */
    public function updated(Payment $payment)
    {
        //
    }

    /**
     * Handle the Payment "deleted" event.
     *
     * @return void
     */
    public function deleted(Payment $payment)
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     *
     * @return void
     */
    public function restored(Payment $payment)
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Payment $payment)
    {
        //
    }
}
