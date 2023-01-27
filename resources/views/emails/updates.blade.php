<x-mail :subject="$subject">

    <ul role="list" class="divide-y bg-white rounded-md shadow">
        @foreach($updates as $audit)
            <li>
                
                @if($audit->event == 'updated')

                    <div class="flex space-x-3 px-4 py-4 sm:px-6">
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium">{{ $audit->auditable->url }}</h3>
                                <p class="text-sm text-gray-500">{{ date_format($audit->created_at, 'd/m/Y H:i') }}</p>
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

                                @if($attribute == 'sale')
                                    <p class="text-sm text-gray-500">
                                        @if(isset($value['old']) && isset($value['new']))
                                        <span class="break-words">
                                            {{
                                                __('email.attributes.updated', [
                                                    'attribute' => $attribute,
                                                    'old' => $value['old'],
                                                    'new' => $value['new'],
                                                ])
                                            }}
                                        </span>
                                        @endif

                                        @if(isset($value['old']) && !isset($value['new']))
                                        <span class="break-words">
                                                {{
                                                    __('email.attributes.new', [
                                                        'attribute' => $attribute,
                                                        'new' => $value['new'],
                                                    ])
                                                }}
                                            </span>
                                        @endif

                                        @if(!isset($value['old']) && isset($value['new']))
                                        <span class="break-words">
                                            {{
                                                __('email.attributes.to_blank', [
                                                    'attribute' => $attribute,
                                                    'old' => $value['old'],
                                                ])
                                            }}
                                        </span>
                                        @endif
                                    </p>
                                @endif

                                @if(in_array($attribute, ['gambling', 'cdb', 'cripto', 'sponsor']))
                                <p class="text-sm text-gray-500">
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
                                <p class="text-sm text-gray-500">
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

                    <div class="flex space-x-3 px-4 py-4 sm:px-6">
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium">{{ $audit->auditable->url }}</h3>
                                <p class="text-sm text-gray-500">{{ date_format($audit->created_at, 'd/m/Y H:i') }}</p>
                            </div>

                            <p class="text-sm text-gray-500">
                                <span class="break-words">
                                    {{ __('Was deleted') }}
                                </span>
                            </p>
                        </div>
                    </div>
                @endif

                @if($audit->event == 'restored')

                    <div class="flex space-x-3 px-4 py-4 sm:px-6">
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium">{{ $audit->auditable->url }}</h3>
                                <p class="text-sm text-gray-500">{{ date_format($audit->created_at, 'd/m/Y H:i') }}</p>
                            </div>

                            <p class="text-sm text-gray-500">
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

</x-mail>