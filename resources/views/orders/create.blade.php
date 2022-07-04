<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Adicionar pedido') }}
        </h2>
    </x-slot>

    <div id="app" class="py-12">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-2 gap-6">

                            <div class="col-span-2">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <div class="mt-1">

                                    <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('status') border-red-300 @enderror>
                                        <option value="">Selecione</option>
                                        @foreach ($statuses as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="site_id" class="block text-sm font-medium text-gray-700">Site</label>
                                <div class="mt-1">

                                    <select id="site_id" name="site_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('site_id') border-red-300 @enderror>
                                        <option value="">Selecione</option>
                                        @foreach ($sites as $site)
                                        <option value="{{ $site->id }}">{{ $site->url }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                @error('site_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="client_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                                <div class="mt-1">

                                    <select id="client_id" name="client_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('client_id') border-red-300 @enderror>
                                        <option value="">Selecione</option>
                                        @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                @error('client_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="seller_id" class="block text-sm font-medium text-gray-700">Vendeddor</label>
                                <div class="mt-1">

                                    <select id="seller_id" name="seller_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('seller_id') border-red-300 @enderror>
                                        <option value="">Selecione</option>
                                        @foreach ($sellers as $seller)
                                        <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                @error('seller_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                                <div class="mt-1">
                                    <input type="text" name="url" id="url"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('url') border-red-300 @enderror" />
                                </div>

                                @error('url')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="receipt_date" class="block text-sm font-medium text-gray-700">Data de recebimento</label>
                                <div class="mt-1">
                                    <input type="date" name="receipt_date" id="receipt_date"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('receipt_date') border-red-300 @enderror" />
                                </div>

                                @error('receipt_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="delivery_date" class="block text-sm font-medium text-gray-700">Data de entrega</label>
                                <div class="mt-1">
                                    <input type="date" name="delivery_date" id="delivery_date"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('delivery_date') border-red-300 @enderror" />
                                </div>

                                @error('delivery_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="payment_date" class="block text-sm font-medium text-gray-700">Data do pagamento</label>
                                <div class="mt-1">
                                    <input type="date" name="payment_date" id="payment_date"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('payment_date') border-red-300 @enderror" />
                                </div>

                                @error('payment_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="charged" class="block text-sm font-medium text-gray-700">Valor cobrado</label>
                                <div class="mt-1">
                                    <input v-model.lazy="charged" v-money="format" type="text" name="charged" id="charged"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('charged') border-red-300 @enderror" />
                                </div>

                                @error('charged')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="paid" class="block text-sm font-medium text-gray-700">Valor pago</label>
                                <div class="mt-1">
                                    <input v-model.lazy="paid" v-money="format" type="text" name="paid" id="paid"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('paid') border-red-300 @enderror" />
                                </div>

                                @error('paid')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="markup" class="block text-sm font-medium text-gray-700">Markup</label>
                                <div class="mt-1">
                                    <input v-model.lazy="markup" v-money="format" type="text" name="markup" id="markup"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('markup') border-red-300 @enderror" />
                                </div>

                                @error('markup')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="comission" class="block text-sm font-medium text-gray-700">Comissão</label>
                                <div class="mt-1">
                                    <input v-model.lazy="comission" v-money="format" type="text" name="comission" id="comission"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('comission') border-red-300 @enderror" />
                                </div>

                                @error('comission')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-6 text-right">
                            <button type="submit"
                                class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Salvar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/v-money@0.8.1/dist/v-money.js"></script>

    <script>
        new Vue({
            el: '#app',
            data() {
                return {
                    charged: '',
                    paid: '',
                    markup: '',
                    comission: '',

                    format: {
                        thousands: '.',
                        decimal: ',',
                        prefix: 'R$ ',
                        suffix: '',
                        precision: 2,
                    },
                }
            },
        });
    </script>
</x-app-layout>
