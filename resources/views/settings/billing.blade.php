@extends('layouts.app')
@section('view.class', 'center-all')

@section('content')
<div class="text-center mb-4">
    <h1>Billing details</h1>
</div>

<form class="formbox mb-8" action="{{ route('settings.billing') }}" method="POST">
    {{ method_field('PATCH') }}
    {{ csrf_field() }}
    
    <div class="mb-4">
        <label for="name" class="form-label">Credit card</label>

        <div class="mb-4">
            <p class="my-0 flex justify-between">
                @if (auth()->user()->onGenericTrial())
                    No card on file.
                @else
                    <span>{{ auth()->user()->card_brand }} ending in {{ auth()->user()->card_last_four }}</span>
                    <a href="#">Update credit card</a>
                @endif
            </p>
        </div>
    </div>

    <div>
        <label for="plan" class="form-label">Current plan</label>

        <div>
            <select id="plan" name="plan" required>
                @if (auth()->user()->onGenericTrial())
                    <option value="none" selected>7-day trial</option>
                @endif
                
                @foreach ($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->pretty_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-6 flex items-center justify-end">
        <button type="submit" class="btn">
            Update billing details
        </button>
    </div>

</form>

<div class="w-full max-w-sm">
    <h2 class="text-center mb-4">Invoices</h2>

    <table class="w-full">
    @foreach (auth()->user()->invoices() as $invoice)
        <tr>
            <td class="p-2 border-b border-grey text-left" width="33%">{{ $invoice->date()->toFormattedDateString() }}</td>
            <td class="p-2 border-b border-grey text-right" width="33%">{{ $invoice->total() }}</td>
            <td class="p-2 border-b border-grey text-right" width="33%"><a href="/settings/billing/invoice/{{ $invoice->id }}">Download</a></td>
        </tr>
    @endforeach
    </table>
</div>

@endsection