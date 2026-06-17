@extends('layouts.admin')

@section('title', 'Financial Settings')

@php
    $methodOptions = ['cash' => 'Cash', 'card' => 'Card', 'bank_transfer' => 'Bank Transfer', 'online' => 'Online Gateway', 'cheque' => 'Cheque', 'easypaisa' => 'Easypaisa', 'jazzcash' => 'JazzCash'];
    $selectedMethods = old('payment_methods', $s['payment_methods'] ?? []);
@endphp

@section('content')
    <x-settings.page
        title="Financial Settings"
        subtitle="Currency formatting, tax, invoicing, and payment configuration."
        :action="route('settings.financial.update')">

        {{-- Currency & Format --}}
        <x-settings.section title="Currency & Number Format" icon="payments">
            <div class="grid grid-cols-1 gap-md md:grid-cols-3">
                <x-settings.field label="Base Currency" name="base_currency" required>
                    <x-settings.select name="base_currency">
                        @foreach ($currencies as $code => $name)
                            <option value="{{ $code }}" @selected(old('base_currency', $s['base_currency']) === $code)>{{ $name }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Currency Symbol" name="currency_symbol" required>
                    <x-settings.input name="currency_symbol" maxlength="8"
                        value="{{ old('currency_symbol', $s['currency_symbol']) }}"/>
                </x-settings.field>
                <x-settings.field label="Symbol Position" name="currency_position" required>
                    <x-settings.select name="currency_position">
                        <option value="before" @selected(old('currency_position', $s['currency_position']) === 'before')>Before amount (₨ 1,000)</option>
                        <option value="after" @selected(old('currency_position', $s['currency_position']) === 'after')>After amount (1,000 ₨)</option>
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Decimal Places" name="decimal_places" required>
                    <x-settings.input type="number" name="decimal_places" min="0" max="4"
                        value="{{ old('decimal_places', $s['decimal_places']) }}"/>
                </x-settings.field>
                <x-settings.field label="Thousand Separator" name="thousand_separator">
                    <x-settings.input name="thousand_separator" maxlength="2"
                        value="{{ old('thousand_separator', $s['thousand_separator']) }}"/>
                </x-settings.field>
                <x-settings.field label="Decimal Separator" name="decimal_separator" required>
                    <x-settings.input name="decimal_separator" maxlength="2"
                        value="{{ old('decimal_separator', $s['decimal_separator']) }}"/>
                </x-settings.field>
                <x-settings.field label="Rounding" name="rounding" required>
                    <x-settings.select name="rounding">
                        @foreach (['none' => 'No rounding', 'nearest' => 'Nearest whole', 'up' => 'Round up', 'down' => 'Round down'] as $val => $lbl)
                            <option value="{{ $val }}" @selected(old('rounding', $s['rounding']) === $val)>{{ $lbl }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- Tax --}}
        <x-settings.section title="Tax" icon="receipt_long">
            <div class="mb-md rounded-lg border border-outline-variant p-4">
                <x-settings.toggle name="tax_enabled" label="Enable Tax on Invoices"
                    :checked="old('tax_enabled', $s['tax_enabled'])"/>
            </div>
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Tax Name" name="tax_name">
                    <x-settings.input name="tax_name" maxlength="50"
                        value="{{ old('tax_name', $s['tax_name']) }}" placeholder="GST / VAT"/>
                </x-settings.field>
                <x-settings.field label="Tax Rate (%)" name="tax_rate" required>
                    <x-settings.input type="number" step="0.01" min="0" max="100" name="tax_rate"
                        value="{{ old('tax_rate', $s['tax_rate']) }}"/>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- Invoicing & Payments --}}
        <x-settings.section title="Invoicing & Payments" icon="request_quote">
            <div class="grid grid-cols-1 gap-md md:grid-cols-3">
                <x-settings.field label="Invoice Prefix" name="invoice_prefix">
                    <x-settings.input name="invoice_prefix" maxlength="20"
                        value="{{ old('invoice_prefix', $s['invoice_prefix']) }}"/>
                </x-settings.field>
                <x-settings.field label="Receipt Prefix" name="receipt_prefix">
                    <x-settings.input name="receipt_prefix" maxlength="20"
                        value="{{ old('receipt_prefix', $s['receipt_prefix']) }}"/>
                </x-settings.field>
                <x-settings.field label="Fiscal Year Start" name="fiscal_year_start" required>
                    <x-settings.select name="fiscal_year_start">
                        @foreach ($months as $m)
                            <option value="{{ $m }}" @selected(old('fiscal_year_start', $s['fiscal_year_start']) === $m)>{{ $m }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Default Payment Terms (days)" name="payment_terms_days" required>
                    <x-settings.input type="number" min="0" max="365" name="payment_terms_days"
                        value="{{ old('payment_terms_days', $s['payment_terms_days']) }}"/>
                </x-settings.field>
                <x-settings.field label="Accepted Payment Methods" name="payment_methods" class="md:col-span-2">
                    <x-settings.select name="payment_methods[]" multiple data-select2-parent placeholder="Select methods...">
                        @foreach ($methodOptions as $val => $lbl)
                            <option value="{{ $val }}" @selected(in_array($val, $selectedMethods))>{{ $lbl }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
            </div>

            <div class="mt-md rounded-lg border border-outline-variant p-4">
                <x-settings.toggle name="late_fee_enabled" label="Charge Late Fees"
                    :checked="old('late_fee_enabled', $s['late_fee_enabled'])"/>
                <div class="mt-md grid grid-cols-1 gap-md md:grid-cols-2">
                    <x-settings.field label="Late Fee Type" name="late_fee_type" required>
                        <x-settings.select name="late_fee_type">
                            <option value="fixed" @selected(old('late_fee_type', $s['late_fee_type']) === 'fixed')>Fixed amount</option>
                            <option value="percent" @selected(old('late_fee_type', $s['late_fee_type']) === 'percent')>Percentage of due</option>
                        </x-settings.select>
                    </x-settings.field>
                    <x-settings.field label="Late Fee Amount / Rate" name="late_fee_amount" required>
                        <x-settings.input type="number" step="0.01" min="0" name="late_fee_amount"
                            value="{{ old('late_fee_amount', $s['late_fee_amount']) }}"/>
                    </x-settings.field>
                </div>
            </div>
        </x-settings.section>
    </x-settings.page>
@endsection
