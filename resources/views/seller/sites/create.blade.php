<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Adicionar site') }}
        </h2>
    </x-slot>

    <div id="app" class="py-12">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('seller.sites.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                                <div class="mt-1">
                                    <input type="text" name="url" id="url" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('url') border-red-300 @enderror" placeholder="Ex.: ocp.news" />
                                </div>

                                @error('url')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                                <div class="mt-1">
                                    <input type="text" name="name" id="name"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 @enderror"
                                        placeholder="Ex.: OCP News" />
                                </div>

                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700">Descrição</label>
                                <div class="mt-1">
                                    <input type="text" name="description" id="description"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('description') border-red-300 @enderror"
                                        placeholder="Ex.: Portal de notícias" />
                                </div>

                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div> -->

                            <div class="col-span-2">
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                                <div class="mt-1">

                                    <select id="category_id" name="category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('category_id') border-red-300 @enderror>
                                        <option value="">Selecione</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                @error('category_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="da" class="block text-sm font-medium text-gray-700">DA</label>
                                <div class="mt-1">
                                    <input type="number" name="da" id="da" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('da') border-red-300 @enderror" />
                                </div>

                                @error('da')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="dr" class="block text-sm font-medium text-gray-700">DR</label>
                                <div class="mt-1">
                                    <input type="number" name="dr" id="dr" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('dr') border-red-300 @enderror" />
                                </div>

                                @error('dr')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="traffic" class="block text-sm font-medium text-gray-700">Tráfego</label>
                                <div class="mt-1">
                                    <input type="number" name="traffic" id="traffic" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('traffic') border-red-300 @enderror" />
                                </div>

                                @error('traffic')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="tf" class="block text-sm font-medium text-gray-700">TF</label>
                                <div class="mt-1">
                                    <input type="number" name="tf" id="tf" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('tf') border-red-300 @enderror" />
                                </div>

                                @error('tf')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-1">
                                <label for="language_id" class="block text-sm font-medium text-gray-700">Linguagem</label>
                                <div class="mt-1">

                                    <select id="language_id" name="language_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('language_id') border-red-300 @enderror>
                                        <option value="">Selecione</option>
                                        @foreach ($languages as $language)
                                        <option value="{{ $language->id }}">{{ $language->name }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                @error('language_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-1">
                                <label for="country_id" class="block text-sm font-medium text-gray-700">País</label>
                                <div class="mt-1">

                                    <select id="country_id" name="country_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('country_id') border-red-300 @enderror>
                                        <option value="">Selecione</option>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                @error('country_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="link_type" class="block text-sm font-medium text-gray-700">Tipo de link</label>
                                <div class="mt-1">

                                    <select id="link_type" name="link_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('link_type') border-red-300 @enderror>
                                        <option value="">Selecione</option>
                                        <option value="DOFOLLOW">DOFOLLOW</option>
                                        <option value="NOFOLLOW">NOFOLLOW</option>

                                    </select>

                                </div>

                                @error('link_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2 space-y-2">
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input value="1" id="gambling" name="gambling" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="gambling" class="font-medium text-gray-700">Gambling</label>
                                    </div>
                                </div>

                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input value="1" id="cdb" name="cdb" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="cdb" class="font-medium text-gray-700">CDB</label>
                                    </div>
                                </div>

                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input value="1" id="cripto" name="cripto" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="cripto" class="font-medium text-gray-700">Cripto</label>
                                    </div>
                                </div>

                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input value="1" id="sponsor" name="sponsor" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="sponsor" class="font-medium text-gray-700">Publi</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-2">
                                <label for="cost" class="block text-sm font-medium text-gray-700">Custo</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input v-model.lazy="cost" v-money="costFormat" type="text" name="cost" id="cost" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('cost') border-red-300 @enderror" />

                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <label for="cost_coin" class="sr-only">Moeda</label>
                                        <select v-model="costCoin" id="cost_coin" name="cost_coin" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                            <option value="BRL">BRL</option>
                                            <option value="EUR">EUR</option>
                                            <option value="USD">USD</option>
                                        </select>
                                    </div>
                                </div>

                                @error('cost')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="last_posted" class="block text-sm font-medium text-gray-700">Último post</label>
                                <div class="mt-1">
                                    <input type="date" name="last_posted" id="last_posted" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('last_posted') border-red-300 @enderror" />
                                </div>

                                @error('last_posted')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="obs" class="block text-sm font-medium text-gray-700">Observações</label>
                                <div class="mt-1">
                                    <textarea type="text" name="obs" id="obs" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('obs') border-red-300 @enderror" placeholder="Insira suas anotações aqui"></textarea>
                                </div>

                                @error('obs')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 text-right">
                            <button type="submit" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Salvar</button>
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
                    coins: @json($coins),

                    cost: '',
                    
                    costCoin: 'BRL',
                }
            },

            computed: {
                costFormat() {
                    return this.coins[this.costCoin];
                },
            },
        });
    </script>
</x-app-layout>