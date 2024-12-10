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

    <body class="font-sans antialiased">        
            
        <nav class="bg-rauzee-dark-500">
            <div class="max-w-7xl mx-auto px-5">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0 py-6 md:py-6">

                    <div class="flex-shrink-0 flex items-center">
                        <a href="https://rauzee.com">
                            <svg class="block h-6 w-auto" xmlns="http://www.w3.org/2000/svg" id="logo-horizontal-rauzee" width="202.273" height="48.738" viewBox="0 0 202.273 48.738">
                                <g id="Grupo_24" data-name="Grupo 24" transform="translate(61.252 13.489)">
                                    <path id="Caminho_3" data-name="Caminho 3" d="M549.511,126.257v13.19h-7.694V104.2h14.2q6.828,0,6.828,6.1v9.865q0,4.877-4.335,5.875l6.068,13.412h-8.29l-5.418-13.191Zm0-16.239V120.66h4.172a1.566,1.566,0,0,0,1.084-.3,1.36,1.36,0,0,0,.325-1.026V111.4a1.236,1.236,0,0,0-1.409-1.385Z" transform="translate(-541.817 -104.2)" fill="#fff"></path>
                                    <path id="Caminho_4" data-name="Caminho 4" d="M631.123,139.448l-1.192-6.374h-7.858l-1.192,6.374h-7.639l7.8-35.248h9.863l8.019,35.248Zm-7.965-12.137h5.635L625.976,112.4Z" transform="translate(-588.627 -104.2)" fill="#fff"></path>
                                    <path id="Caminho_5" data-name="Caminho 5" d="M706.541,104.2h7.748v29.1q0,6.151-6.881,6.152h-7.261q-6.827,0-6.827-6.152V104.2h7.748v28.044a1.235,1.235,0,0,0,1.408,1.385h2.6q1.463,0,1.463-1.385Z" transform="translate(-641.106 -104.2)" fill="#fff"></path>
                                    <path id="Caminho_6" data-name="Caminho 6" d="M783.917,104.2v5.819l-12.3,23.608h11.975v5.819H763.6v-5.817l12.3-23.608H764.251V104.2Z" transform="translate(-687.164 -104.2)" fill="#fff"></path>
                                    <path id="Caminho_7" data-name="Caminho 7" d="M839.891,133.629h11.054v5.819h-18.8V104.2h18.369v5.819h-10.62v8.591h9.049v5.821h-9.049Z" transform="translate(-732.084 -104.2)" fill="#fff"></path>
                                    <path id="Caminho_8" data-name="Caminho 8" d="M904.193,133.629h11.054v5.819h-18.8V104.2h18.369v5.819H904.193v8.591h9.049v5.821h-9.049Z" transform="translate(-774.224 -104.2)" fill="#fff"></path>
                                </g>
                                <path id="Caminho_9" data-name="Caminho 9" d="M412.639,100.137V78.577L388.778,65.06,364.092,78.565V86.6L388.756,73.1l16.868,9.611v4.982l-16.86-9.828-24.67,14.624V113.8l7.076-4.2V96.117l17.59-10.167Z" transform="translate(-364.092 -65.06)" fill="#fff"></path>
                                <path id="Caminho_10" data-name="Caminho 10" d="M420.38,139.519l-6.946,4.3L444.976,162.5v-8.289Z" transform="translate(-396.429 -113.857)" fill="#fff"></path>
                            </svg>
                        </a>
                    </div>

                </div>
            </div>
        </nav>

        <main>
            <div class="p-5 space-y-6">
                <div>
                    <div class="space-y-6">
                        <div class="bg-white border border-gray-200 rounded-md overflow-hidden break-before">
                            <div>
                                <dl class="divide-y divide-gray-200 grid grid-cols-1 md:grid-cols-6">

                                    <div class="col-span-6 px-6 py-4">
                                        <h3 class="text-base font-semibold leading-7 text-gray-900">
                                            Análise de crédito
                                        </h3>

                                        <span class="flex flex-col text-sm text-gray-700">
                                            <span>{{ $loan->clients[0]->name }}</span>
                                            <span>{{ $loan->clients[0]->formatted_cpf_cnpj }}</span>
                                        </span>
                                    </div>

                                    <div class="col-span-3 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Status</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $simulation->formatted_status ?? '-' }}
                                        </dd>
                                    </div>

                                    <div class="col-span-3 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Validade</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $simulation->validity?->format('d/m/Y') ?? '-' }}
                                        </dd>
                                    </div>

                                    <div class="col-span-2 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Escritório</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $simulation->office?->name ?? '-' }}
                                        </dd>
                                    </div>

                                    <div class="col-span-2 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Banco</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $simulation->bank ? $simulation->bank->name : '-' }}
                                        </dd>
                                    </div>

                                    <div class="col-span-2 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Agência</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $simulation->agency ? $simulation->agency->name : '-' }}
                                        </dd>
                                    </div>

                                    <div class="col-span-6 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Modalidade/Tipo</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $loanTypes[$simulation->type] ?? '-' }}
                                        </dd>
                                    </div>

                                    <div class="col-span-3 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Indexador</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $simulation->formatted_indexer ?? '-' }}
                                        </dd>
                                    </div>

                                    <div class="col-span-3 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Contrato</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $simulation->contract ?? '-' }}
                                        </dd>
                                    </div>

                                    <div class="col-span-3 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">
                                            Três anos de recolhimento do FGTS
                                        </dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            @if ($simulation->estate_long_work === true)
                                            Sim
                                            @elseif($simulation->estate_long_work === false)
                                            Não
                                            @else
                                            -
                                            @endif
                                        </dd>
                                    </div>

                                    <div class="col-span-3 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">
                                            Sistema de amortização
                                        </dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $simulation->estate_loan }}
                                        </dd>
                                    </div>

                                    <div class="col-span-2 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Valor compra</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $simulation->formatted_purchase_value ?? 0 }}
                                        </dd>
                                    </div>

                                    <div class="col-span-2 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Valor avaliação</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $loan->engineering?->formatted_evaluation ?? "-" }}
                                        </dd>
                                    </div>

                                    <div class="col-span-2 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Saldo devedor</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $simulation->formatted_balance ?? 0 }}
                                        </dd>
                                    </div>

                                    <div class="col-span-2 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Valor aprovado</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $simulation->formatted_approved_value ?? 0 }}
                                        </dd>
                                    </div>

                                    <div class="col-span-2 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Valor subsídio</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $simulation->formatted_sub_value ?? 0 }}
                                        </dd>
                                    </div>

                                    <div class="col-span-2 px-6 py-4 sm:grid sm:grid-cols-1">
                                        <dt class="text-sm font-medium text-gray-900">Valor FGTS</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ $loan->formatted_total_fgts_value }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>