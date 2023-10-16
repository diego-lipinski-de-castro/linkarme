<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Linkarme</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-white">

        <div class="bg-black py-4"></div>

        <div class="py-4 relative">
            <img src="{{ asset('login-logo.png') }}" class="mx-auto"/>

            <img src="{{ asset('globo.png') }}" class="absolute right-0 -top-3"/>

            <div class="absolute h-4 bg-transparent bg-gradient-to-t from-black/30 via-transparent to-transparent bottom-0 w-full"></div>
        </div>

        <div class="bg-[#0aa1ed] bg-gradient-to-r from-[#0aa1ed] to-black text-white px-6 py-4 text-xl">
            Hello! Please pay attention to our last link updates!
        </div>

        <div class="text-black px-6 py-4 font-bold">
            We just have the following changes on our base:
        </div>

        <ul role="list" class="">
            @foreach($updates as $audit)
            <li class="even:bg-gray-100">

                @if($audit->event == 'updated')
                <div class="flex items-center space-x-3 px-4 py-4 sm:px-6">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                    </svg>

                    <div class="flex-1 space-y-1 ml-3">
                        <div class="flex-col flex justify-between">
                            <a href="#" class="text-base font-medium underline text-blue-500">{{ $audit->auditable->url }}</a>
                            <p class="mt-1 text-sm text-gray-500">{{ date_format($audit->created_at, 'd/m/Y H:i') }}</p>
                        </div>

                        @foreach($audit->modified as $attribute => $value)

                            @php

                                if($attribute == 'sale') {
                                    $value['old'] = App\Helper::formatCurrency($value['old'] / 100, $audit->modified['sale_coin']['old']);
                                    $value['new'] = App\Helper::formatCurrency($value['new'] / 100, $audit->modified['sale_coin']['new']);
                                }

                                if(in_array($attribute, ['gambling', 'cdb', 'cripto', 'sponsor'])) {
                                    $value['old'] = (bool) $value['old'];
                                    $value['new'] = (bool) $value['new'];
                                }

                            @endphp

                            @if($attribute == 'sale' && isset($value['new']) && isset($value['old']) && $audit->modified['sale']['old'] != 0)
                            <p class="text-base text-gray-900">
                                <span class="break-words">
                                    {{
                                                        __('email.attributes.updated', [
                                                            'attribute' => $attribute,
                                                            'old' => $value['old'],
                                                            'new' => $value['new'],
                                                        ])
                                                    }}
                                </span>
                            </p>
                            @endif

                            @if(in_array($attribute, ['sponsor']))
                            <p class="text-base text-gray-900">
                                @if(isset($value['old']) && isset($value['new']))

                                @if($value['new'] == true)
                                <span class="break-words">
                                    {{
                                                        __('email.sponsor.bool_true', [
                                                            'attribute' => $attribute,
                                                            'old' => $value['old'],
                                                            'new' => $value['new'],
                                                        ])
                                                    }}
                                </span>
                                @endif

                                @if($value['new'] == false)
                                <span class="break-words">
                                    {{
                                                        __('email.sponsor.bool_false', [
                                                            'attribute' => $attribute,
                                                            'old' => $value['old'],
                                                            'new' => $value['new'],
                                                        ])
                                                    }}
                                </span>
                                @endif

                                @endif
                            </p>
                            @endif

                            @if(in_array($attribute, ['gambling', 'cdb', 'cripto']))
                            <p class="text-base text-gray-900">
                                @if(isset($value['old']) && isset($value['new']))

                                @if($value['new'] == true)
                                <span class="break-words">
                                    {{
                                                        __('email.attributes.bool_true', [
                                                            'attribute' => $attribute,
                                                            'old' => $value['old'],
                                                            'new' => $value['new'],
                                                        ])
                                                    }}
                                </span>
                                @endif

                                @if($value['new'] == false)
                                <span class="break-words">
                                    {{
                                                        __('email.attributes.bool_false', [
                                                            'attribute' => $attribute,
                                                            'old' => $value['old'],
                                                            'new' => $value['new'],
                                                        ])
                                                    }}
                                </span>
                                @endif

                                @endif
                            </p>
                            @endif

                            @if($attribute == 'status' && $value['new'] == 'APPROVED')
                            <p class="text-base text-gray-900">
                                <span class="break-words">
                                    {{ __('Recently added') }}
                                </span>
                            </p>
                            @endif

                        @endforeach
                    </div>
                </div>
                @endif

                @if($audit->event == 'deleted')
                <div class="flex items-center space-x-3 px-4 py-4 sm:px-6">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <div class="flex-1 space-y-1 ml-3">
                        <div class="flex-col flex justify-between">
                            <a href="#" class="text-base font-medium underline text-blue-500">{{ $audit->auditable->url }}</a>
                            <p class="mt-1 text-sm text-gray-500">{{ date_format($audit->created_at, 'd/m/Y H:i') }}</p>
                        </div>

                        <p class="text-base text-gray-900">
                            <span class="break-words">
                                {{ __('Was deleted') }}
                            </span>
                        </p>
                    </div>
                </div>
                @endif

                @if($audit->event == 'restored')

                <div class="flex items-center space-x-3 px-4 py-4 sm:px-6">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                    </svg>

                    <div class="flex-1 space-y-1 ml-3">
                        <div class="flex-col flex justify-between">
                            <a href="#" class="text-base font-medium underline text-blue-500">{{ $audit->auditable->url }}</a>
                            <p class="mt-1 text-sm text-gray-500">{{ date_format($audit->created_at, 'd/m/Y H:i') }}</p>
                        </div>

                        <p class="text-base text-gray-900">
                            <span class="break-words">
                                {{ __('Was restored') }}
                            </span>
                        </p>
                    </div>
                </div>
                @endif

            </li>
            @endforeach
        </ul>

        <div class="text-black px-6 py-4 font-bold">
            ...and that was all for the moment.
        </div>

        <div class="bg-[#0aa1ed] bg-gradient-to-r from-[#0aa1ed] to-black h-40 text-white px-6 py-4 font-bold text-base">
            Thank you for working with with us!
        </div>

    </body>

</html>