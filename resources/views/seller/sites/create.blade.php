<x-app-seller-layout>
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
                                    <input @blur="check" v-model="url" type="text" name="url" id="url" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('url') border-red-300 @enderror" placeholder="Ex.: ocp.news" />
                                </div>

                                <p v-if="exists" class="mt-2 text-sm text-red-500">Verificamos que já existe um site com essa url. <button type="button" @click="showModal = true" class="text-blue-500">Clique aqui para fazer uma proposta de valor</button></p>

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

                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input value="1" id="menu" name="menu" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="menu" class="font-medium text-gray-700">Link menu</label>
                                    </div>
                                </div>

                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input value="1" id="banner" name="banner" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="banner" class="font-medium text-gray-700">Banners</label>
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

                            <div class="col-span-2">
                                <label for="owner_name" class="block text-sm font-medium text-gray-700">Nome dono</label>
                                <div class="mt-1">
                                    <input type="text" name="owner_name" id="owner_name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('owner_name') border-red-300 @enderror" />
                                </div>

                                @error('owner_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="owner_email" class="block text-sm font-medium text-gray-700">E-mail dono</label>
                                <div class="mt-1">
                                    <input type="text" name="owner_email" id="owner_email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('owner_email') border-red-300 @enderror" />
                                </div>

                                @error('owner_email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="owner_phone" class="block text-sm font-medium text-gray-700">Telefone dono</label>
                                <div class="mt-1">
                                    <input type="text" name="owner_phone" id="owner_phone" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('owner_phone') border-red-300 @enderror" />
                                </div>

                                @error('owner_phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="bank" class="block text-sm font-medium text-gray-700">Informações bancárias</label>
                                <div class="mt-1">
                                    <textarea type="text" name="bank" id="bank" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('bank') border-red-300 @enderror"></textarea>
                                </div>

                                @error('bank')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="pix" class="block text-sm font-medium text-gray-700">PIX site</label>
                                <div class="mt-1">
                                    <input type="text" name="pix" id="pix" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('pix') border-red-300 @enderror" />
                                </div>

                                @error('pix')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Telefone site</label>
                                <div class="mt-1">
                                    <input type="text" name="phone" id="phone" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('phone') border-red-300 @enderror" />
                                </div>

                                @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="paypal" class="block text-sm font-medium text-gray-700">Paypal site</label>
                                <div class="mt-1">
                                    <input type="text" name="paypal" id="paypal" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('paypal') border-red-300 @enderror" />
                                </div>

                                @error('paypal')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="instagram" class="block text-sm font-medium text-gray-700">Instagram site</label>
                                <div class="mt-1">
                                    <input type="text" name="instagram" id="instagram" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('instagram') border-red-300 @enderror" />
                                </div>

                                @error('instagram')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="facebook" class="block text-sm font-medium text-gray-700">Facebook site</label>
                                <div class="mt-1">
                                    <input type="text" name="facebook" id="facebook" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('facebook') border-red-300 @enderror" />
                                </div>

                                @error('facebook')
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

        <div class="overflow-auto" style="background-color: rgba(0,0,0,0.5)" v-show="showModal" :class="{ 'fixed inset-0 z-10 flex items-center justify-center': showModal }">
            <div class="bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg py-4 text-left px-6" v-show="showModal">

                <form action="{{ route('seller.sites.offer') }}" method="POST" class="col-span-2">

                    @csrf

                    <input type="hidden" :value="url" name="url">

                    <div class="flex justify-between items-center mb-5">
                        <p class="text-2xl font-bold">Enviar proposta pelo portal</p>
                        <div class="cursor-pointer z-50" @click="showModal = false">
                            <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <label for="offer_cost" class="block text-sm font-medium text-gray-700">Valor</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input v-model.lazy="offerCost" v-money="offerCostFormat" type="text" name="offer_cost" id="offer_cost" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('cost') border-red-300 @enderror" />

                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <label for="offer_cost_coin" class="sr-only">Moeda</label>
                                <select v-model="offerCostCoin" id="offer_cost_coin" name="offer_cost_coin" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                    <option value="BRL">BRL</option>
                                    <option value="EUR">EUR</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                        </div>

                        @error('offer_cost')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>           

                    <div class="flex justify-end mt-5">
                        <button type="button" class="bg-transparent px-2 py-1 rounded-md text-indigo-500 hover:bg-gray-100 hover:text-indigo-700 mr-2"  @click="showModal = false">Voltar</button>
                        <button type="submit" class="modal-close px-4 bg-indigo-500 px-2 py-1 rounded-md text-white hover:bg-indigo-700">Enviar</button>
                    </div>

                </form>
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
                    showModal: false,
                    url: '',
                    exists: null,

                    coins: @json($coins),

                    cost: '',
                    costCoin: 'BRL',

                    offerCost: '',
                    offerCostCoin: 'BRL',
                }
            },

            computed: {
                costFormat() {
                    return this.coins[this.costCoin];
                },

                offerCostFormat() {
                    return this.coins[this.offerCostCoin];
                },
            },

            methods: {
                async check() {
                    try {
                        const res = await fetch(`{{ route('seller.sites.check') }}?url=${this.url}`)
                        const data = await res.json()
                        this.exists = data.exists
                    } catch (error) {
                        console.log(error)
                        this.exists = null
                    }
                },
            },
        });
    </script>
</x-app-seller-layout>