@extends('layouts.app')
@section('view.class', 'center-all')

@section('content')
<div class="text-center mb-4">
    <h1>Billing details</h1>
</div>

<billing-form 
    :plans="{{ $plans }}" 
    :errors="{{ $errors }}"
    action="{{ route('settings.billing') }}"
    action-cancel="{{ route('settings.billing.cancel') }}"
></billing-form>

<div class="w-full max-w-sm">
    <h2 class="text-center mb-4">Invoices</h2>

    <table class="w-full">
    @if (auth()->user()->subscribed('main'))
    @foreach (auth()->user()->invoices() as $invoice)
        <tr>
            <td class="p-2 border-b border-grey text-left" width="33%">{{ $invoice->date()->toFormattedDateString() }}</td>
            <td class="p-2 border-b border-grey text-right" width="33%">{{ $invoice->total() }}</td>
            <td class="p-2 border-b border-grey text-right" width="33%"><a href="/settings/billing/invoice/{{ $invoice->id }}">Download</a></td>
        </tr>
    @endforeach
    @else
        <tr>
            <td class="text-center text-sm text-grey-dark mt-2 mb-0">
                As a trial user you have no invoices on file.<br>
                Your first invoice will appear here once you upgrade your plan.
            </td>
        </tr>
    @endif
    </table>
</div>

@endsection