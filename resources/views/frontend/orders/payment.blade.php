@extends('layouts.frontend')

@section('title', 'Complete Payment - ' . config('app.name'))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16" x-data="{ redirecting: false, selectedMethod: '{{ $order->payment_method ?? 'sslcommerz' }}' }">

    {{-- Progress Stepper --}}
    <div class="flex items-center justify-center gap-2 sm:gap-4 text-xs font-semibold uppercase tracking-widest mb-10 sm:mb-14">
        <span class="flex items-center gap-2 text-neutral-400">
            <span class="w-6 h-6 rounded-full bg-neutral-200 text-neutral-600 flex items-center justify-center text-[10px] font-bold">✓</span>
            <span class="hidden sm:inline">Cart</span>
        </span>
        <span class="w-8 sm:w-16 h-px bg-neutral-300"></span>
        <span class="flex items-center gap-2 text-neutral-400">
            <span class="w-6 h-6 rounded-full bg-neutral-200 text-neutral-600 flex items-center justify-center text-[10px] font-bold">✓</span>
            <span class="hidden sm:inline">Checkout</span>
        </span>
        <span class="w-8 sm:w-16 h-px bg-brand-600"></span>
        <span class="flex items-center gap-2 text-brand-600 font-bold">
            <span class="w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center text-[10px] font-bold ring-4 ring-brand-100">3</span>
            <span>Payment</span>
        </span>
    </div>

    {{-- Main 2-Column Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

        {{-- Left Column: Payment Gateways (7 Cols) --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- Flash messages --}}
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 text-sm font-medium rounded-none flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-bold">Payment Issue</p>
                        <p class="text-xs text-red-600 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Payment Header Card --}}
            <div class="bg-white border border-neutral-200 p-6 sm:p-8">
                <div class="flex items-center justify-between border-b border-neutral-100 pb-5 mb-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-neutral-400">Order Invoice</p>
                        <p class="text-xl font-bold font-mono text-neutral-900 mt-0.5">{{ $order->invoice_no }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold uppercase tracking-widest text-neutral-400">Amount Due</p>
                        <p class="text-2xl sm:text-3xl font-extrabold text-brand-600 font-mono mt-0.5">
                            {{ setting('currency_symbol', '৳') }}{{ number_format($order->total) }}
                        </p>
                    </div>
                </div>

                {{-- SSLCommerz Gateway Section --}}
                <div x-show="selectedMethod === 'sslcommerz'" class="space-y-6">
                    <div class="border-2 border-blue-500 bg-blue-50/30 p-5 sm:p-6 relative">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-600 text-white rounded flex items-center justify-center font-bold text-base shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-neutral-900">SSLCommerz Secure Gateway</h3>
                                    <p class="text-xs text-neutral-500">Cards, Mobile Banking &amp; Net Banking</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-[10px] font-bold uppercase px-2 py-0.5 rounded tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600 animate-pulse"></span>
                                Active
                            </span>
                        </div>

                        <p class="text-xs text-neutral-600 leading-relaxed mb-4">
                            You will be redirected to the official SSLCommerz payment portal to complete your transaction with zero extra charge.
                        </p>

                        {{-- Supported Payment Methods Grid --}}
                        <div class="border-t border-blue-100 pt-4 mt-4">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-neutral-500 mb-3">Supported Payment Options</p>
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                <div class="bg-white border border-neutral-200 py-2 px-2 text-center rounded text-xs font-semibold text-neutral-700 shadow-sm flex items-center justify-center gap-1.5">
                                    <span class="font-bold text-blue-700">VISA</span>
                                </div>
                                <div class="bg-white border border-neutral-200 py-2 px-2 text-center rounded text-xs font-semibold text-neutral-700 shadow-sm flex items-center justify-center gap-1.5">
                                    <span class="font-bold text-red-600">Mastercard</span>
                                </div>
                                <div class="bg-white border border-neutral-200 py-2 px-2 text-center rounded text-xs font-semibold text-neutral-700 shadow-sm flex items-center justify-center gap-1.5">
                                    <span class="font-bold text-blue-500">AMEX</span>
                                </div>
                                <div class="bg-white border border-neutral-200 py-2 px-2 text-center rounded text-xs font-semibold text-neutral-700 shadow-sm flex items-center justify-center gap-1.5">
                                    <span class="font-bold text-[#E2136E]">bKash</span>
                                </div>
                                <div class="bg-white border border-neutral-200 py-2 px-2 text-center rounded text-xs font-semibold text-neutral-700 shadow-sm flex items-center justify-center gap-1.5">
                                    <span class="font-bold text-[#F7941D]">Nagad</span>
                                </div>
                                <div class="bg-white border border-neutral-200 py-2 px-2 text-center rounded text-xs font-semibold text-neutral-700 shadow-sm flex items-center justify-center gap-1.5">
                                    <span class="font-bold text-[#8C3494]">Rocket</span>
                                </div>
                                <div class="bg-white border border-neutral-200 py-2 px-2 text-center rounded text-xs font-semibold text-neutral-700 shadow-sm flex items-center justify-center gap-1.5">
                                    <span class="font-bold text-emerald-600">Upay</span>
                                </div>
                                <div class="bg-white border border-neutral-200 py-2 px-2 text-center rounded text-xs font-semibold text-neutral-700 shadow-sm flex items-center justify-center gap-1.5">
                                    <span class="font-bold text-neutral-800">Internet Bank</span>
                                </div>
                            </div>
                        </div>

                        {{-- SSLCommerz Submit Form --}}
                        <form method="POST" action="{{ route('payment.sslcommerz.initiate', array_merge(['order' => $order], $order->user_id ? [] : ['token' => $order->confirmation_token])) }}" class="mt-6" @submit="redirecting = true">
                            @csrf
                            @if(! $order->user_id)
                                <input type="hidden" name="token" value="{{ $order->confirmation_token }}">
                            @endif
                            <button type="submit" :disabled="redirecting" class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-3.5 px-6 rounded-none text-sm uppercase tracking-wider transition-all duration-200 shadow-md flex items-center justify-center gap-2">
                                <span x-show="!redirecting" class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Pay {{ setting('currency_symbol', '৳') }}{{ number_format($order->total) }} with SSLCommerz
                                </span>
                                <span x-show="redirecting" class="inline-flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Connecting to SSLCommerz Gateway…
                                </span>
                            </button>
                        </form>
                    </div>

                    {{-- Switch to bKash button if bKash is configured --}}
                    <div class="text-center pt-2">
                        <button type="button" @click="selectedMethod = 'bkash'" class="text-xs text-neutral-500 hover:text-brand-600 font-semibold underline transition-colors">
                            Want to pay with bKash direct tokenized checkout instead? Click here
                        </button>
                    </div>
                </div>

                {{-- bKash Direct Gateway Section --}}
                <div x-show="selectedMethod === 'bkash'" class="space-y-6">
                    <div class="border-2 border-[#E2136E] bg-[#FDF2F8]/40 p-5 sm:p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#E2136E] text-white rounded flex items-center justify-center font-extrabold text-base shadow-sm">
                                    bK
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-neutral-900">bKash Direct Checkout</h3>
                                    <p class="text-xs text-neutral-500">Official Tokenized Payment</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 bg-[#FCE7F3] text-[#BE185D] text-[10px] font-bold uppercase px-2 py-0.5 rounded tracking-wider">
                                Instant
                            </span>
                        </div>

                        <p class="text-xs text-neutral-600 leading-relaxed mb-4">
                            Pay directly with your personal bKash account in 3 simple steps. Your account credentials and OTP are processed safely on bKash's official portal.
                        </p>

                        {{-- 3-Step Instructions --}}
                        <div class="space-y-2.5 bg-white border border-pink-100 p-4 mb-4 text-xs">
                            <div class="flex items-start gap-2.5">
                                <span class="w-4 h-4 rounded-full bg-[#E2136E] text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0 mt-0.5">1</span>
                                <p class="text-neutral-600">Click below to open the official bKash popup gateway.</p>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span class="w-4 h-4 rounded-full bg-[#E2136E] text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0 mt-0.5">2</span>
                                <p class="text-neutral-600">Enter your bKash wallet mobile number and the OTP sent to your phone.</p>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span class="w-4 h-4 rounded-full bg-[#E2136E] text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0 mt-0.5">3</span>
                                <p class="text-neutral-600">Enter your bKash PIN to confirm payment. Confirmation is instant.</p>
                            </div>
                        </div>

                        {{-- bKash Submit Form --}}
                        <form method="POST" action="{{ route('payment.bkash.initiate', array_merge(['order' => $order], $order->user_id ? [] : ['token' => $order->confirmation_token])) }}" @submit="redirecting = true">
                            @csrf
                            @if(! $order->user_id)
                                <input type="hidden" name="token" value="{{ $order->confirmation_token }}">
                            @endif
                            <button type="submit" :disabled="redirecting" class="w-full bg-[#E2136E] hover:bg-[#C4115B] active:bg-[#9D0E49] text-white font-bold py-3.5 px-6 rounded-none text-sm uppercase tracking-wider transition-all duration-200 shadow-md flex items-center justify-center gap-2">
                                <span x-show="!redirecting" class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Pay {{ setting('currency_symbol', '৳') }}{{ number_format($order->total) }} with bKash
                                </span>
                                <span x-show="redirecting" class="inline-flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Connecting to bKash Gateway…
                                </span>
                            </button>
                        </form>
                    </div>

                    {{-- Switch to SSLCommerz button --}}
                    <div class="text-center pt-2">
                        <button type="button" @click="selectedMethod = 'sslcommerz'" class="text-xs text-neutral-500 hover:text-blue-600 font-semibold underline transition-colors">
                            Want to pay with Cards / Nagad / Rocket / Other Banks via SSLCommerz? Click here
                        </button>
                    </div>
                </div>
            </div>

            {{-- Trust & Security Badges Card --}}
            <div class="bg-neutral-50 border border-neutral-200 p-5 sm:p-6 text-xs text-neutral-600 space-y-3">
                <h4 class="font-bold text-neutral-900 uppercase tracking-wider text-[11px]">Security &amp; Guarantee</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                    <div class="flex items-center gap-2 bg-white border border-neutral-200 p-2.5">
                        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>256-Bit SSL Encrypted</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white border border-neutral-200 p-2.5">
                        <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>PCI-DSS Compliant</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white border border-neutral-200 p-2.5">
                        <svg class="w-4 h-4 text-brand-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Instant Order Confirmation</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column: Order Summary & Delivery Details (5 Cols) --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- Order Summary Card --}}
            <div class="bg-white border border-neutral-200 p-6 sm:p-7">
                <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 border-b border-neutral-200 pb-3 mb-4 flex items-center justify-between">
                    <span>Order Summary</span>
                    <span class="text-xs font-normal text-neutral-500 font-mono">{{ $order->items->sum('qty') }} {{ $order->items->sum('qty') === 1 ? 'item' : 'items' }}</span>
                </h3>

                {{-- Items Mini List --}}
                <div class="divide-y divide-neutral-100 max-h-72 overflow-y-auto pr-1 mb-5">
                    @foreach($order->items as $item)
                        <div class="py-3 flex items-center gap-3">
                            <div class="w-12 h-16 bg-neutral-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-neutral-200">
                                @if($item->book?->cover_image)
                                    <img src="{{ asset('storage/' . $item->book->cover_image) }}" alt="{{ $item->book->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-neutral-400 text-xs font-serif italic">Book</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-neutral-900 truncate">{{ $item->book?->title ?? 'Product #' . $item->book_id }}</p>
                                <p class="text-[11px] text-neutral-500 mt-0.5">
                                    Qty: <span class="font-semibold text-neutral-700">{{ $item->qty }}</span> × {{ setting('currency_symbol', '৳') }}{{ number_format($item->price) }}
                                </p>
                            </div>
                            <p class="text-xs font-bold font-mono text-neutral-900 text-right">
                                {{ setting('currency_symbol', '৳') }}{{ number_format($item->subtotal) }}
                            </p>
                        </div>
                    @endforeach
                </div>

                {{-- Pricing Breakdown --}}
                <div class="border-t border-neutral-200 pt-4 space-y-2.5 text-xs text-neutral-600">
                    <div class="flex justify-between">
                        <span>Items Subtotal</span>
                        <span class="font-semibold text-neutral-900 font-mono">{{ setting('currency_symbol', '৳') }}{{ number_format($order->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Delivery Fee</span>
                        <span class="font-semibold text-neutral-900 font-mono">{{ $order->shipping > 0 ? setting('currency_symbol', '৳') . number_format($order->shipping) : 'Free' }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-green-600 font-medium">
                            <span>Coupon Discount</span>
                            <span class="font-mono">-{{ setting('currency_symbol', '৳') }}{{ number_format($order->discount) }}</span>
                        </div>
                    @endif
                    <div class="border-t border-neutral-200 pt-3 flex justify-between items-baseline font-bold text-base text-neutral-900">
                        <span>Total Payable</span>
                        <span class="text-xl text-brand-600 font-mono">{{ setting('currency_symbol', '৳') }}{{ number_format($order->total) }}</span>
                    </div>
                </div>
            </div>

            {{-- Shipping Address Card --}}
            @if($order->address)
                <div class="bg-white border border-neutral-200 p-6 text-xs text-neutral-600">
                    <h4 class="font-bold text-neutral-900 uppercase tracking-wider text-[11px] border-b border-neutral-100 pb-2.5 mb-3 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Shipping Address
                    </h4>
                    <p class="font-bold text-neutral-900 text-sm mb-1">{{ $order->address->name }}</p>
                    <p class="text-neutral-700 mb-0.5">📞 {{ $order->address->phone }}</p>
                    <p class="text-neutral-600 leading-relaxed">{{ $order->address->address }}, {{ $order->address->area }}, {{ $order->address->district }} @if($order->address->postal_code)- {{ $order->address->postal_code }}@endif</p>
                </div>
            @endif

            {{-- Support & Assistance --}}
            <div class="bg-neutral-50 border border-neutral-200 p-4 text-center text-xs text-neutral-500">
                <p>Need help with your payment?</p>
                <p class="mt-1">
                    Contact our support team at <a href="{{ route('contact') }}" class="text-neutral-900 font-bold underline hover:text-brand-600 transition-colors">Customer Care</a>
                </p>
            </div>

        </div>

    </div>
</div>
@endsection
