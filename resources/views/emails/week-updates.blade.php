<x-mail :subject="$subject">

    <ul role="list" class="divide-y divide-gray-200 bg-white rounded-md shadow">
        @foreach($updates as $audit)
            <li>

                @if($audit->event == 'created')
                    <div class="flex space-x-3 px-4 py-4 sm:px-6">
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium">{{ $audit->auditable->url }}</h3>
                                <p class="text-sm text-gray-500">{{ date_format($audit->created_at, 'd/m/Y H:i') }}</p>
                            </div>
                            <p class="text-sm text-gray-500">
                                {{ __('Recently added') }}
                            </p>
                        </div>
                    </div>
                @endif

                @if($audit->event == 'updated')
                    <!-- {{ $audit }} -->

                    <!-- const format = notification.data.audit_modified.sale_coin[frame]; -->
                    <!-- return app.appContext.config.globalProperties.$filters.currency(value / 100, coins[format]); -->

                    <div class="flex space-x-3 px-4 py-4 sm:px-6">
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium">{{ $audit->auditable->url }}</h3>
                                <p class="text-sm text-gray-500">{{ date_format($audit->created_at, 'd/m/Y H:i') }}</p>
                            </div>
                            
                            <!-- v-for="(value, attribute) in notification.data.audit_modified" -->

                            @foreach($audit->modified as $attribute => $value)

                                @php

                                    if($attribute == 'sale') {

                                        $value['old'] = App\Helper::formatCurrency($value['old'] / 100, $audit->modified['sale_coin']['old']);
                                        $value['new'] = App\Helper::formatCurrency($value['new'] / 100, $audit->modified['sale_coin']['new']);

                                    }

                                @endphp

                                @if(in_array($attribute, ['sale', 'gambling', 'cdb', 'cripto', 'sponsor', 'menu', 'banner']))

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

                                        <!-- <span class="break-words">
                                            {{
                                                __('email.attributes.blank', [
                                                    'attribute' => $attribute,
                                                ])
                                            }}
                                        </span> -->

                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

            </li>
        @endforeach
    </ul>

</x-mail>