<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css'])
        @stack('styles')
    </head>

    <body class="font-sans antialiased max-w-3xl mx-auto">    
            
        <nav>
            <div class="bg-black h-5"></div>
            <img src="{{ asset('login-logo.png') }}" class="mt-5 h-16 w-auto mx-auto" />
        </nav>

        <main class="mt-10">

            <div class="flex justify-between">
                <div class="flex flex-col">
                    <span class="font-bold text-3xl">{{ __('Invoice') }}</span>
                    <span class="font-bold text-indigo-500">#{{ $invoice->number }}</span>
                </div>

                <div>
                    <span class="font-bold text-sm">
                        {{ __('Invoice date') }}: {{ $invoice->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            <div>
                Our data
            </div>

            <div>
                Client data
            </div>

            <div class="bg-indigo-500 h-10 px-10 w-full flex justify-between items-center">
                <span class="font-bold text-white">{{ __('Services') }}</span>
                <span class="font-bold text-white">{{ __('Price') }}</span>
            </div>

            <div class="space-y-2.5 py-5 px-10">
                {{-- {{ $invoice }} --}}

                @foreach ($invoice->orders as $order)
                    @foreach ($order->items as $item)
                        {{-- {{$item}} --}}
                        <div class="w-full flex justify-between items-center">
                            <div class="flex flex-col">
                                <span class="text-black font-bold">Service description</span>
                                <span class="text-blue-500 text-xs">{{ $item->pivot->link }}</span>
                            </div>

                            <span class="text-black">R$ {{ $item->pivot->cost }}</span>
                        </div>
                    @endforeach
                @endforeach
            </div>

            <div>
                Wire details
            </div>
        </main>

        <footer class="bg-indigo-500 px-10 py-5">
            <span class="font-bold text-white">
                {{ __('Thank you for working with us!') }}
            </span>
        </footer>
    </body>
</html>