<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sites') }}
            </h2>

            <a href="{{ route('sites.create') }}" class="inline-flex items-center px-4 px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Adicionar site</a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ columns: $persist(['url', 'country', 'language', 'da', 'dr', 'tf', 'category', 'ssl', 'gambling', 'sponsor', 'cripto']) }">
        <div class="sm:px-6 lg:px-8">

            <div class="flex justify-end mb-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="bg-gray-50 border rounded-md flex items-center px-3 py-1 text-left text-sm font-semibold text-gray-900">
                            <div>Colunas</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        <div class="block px-4 py-2 relative flex">
                            <div class="flex items-center h-5">
                                <input x-model="columns" value="url" id="url" name="url" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="url" class="font-medium text-gray-700">Domínio</label>
                            </div>
                        </div>

                        <div class="block px-4 py-2 relative flex">
                            <div class="flex items-center h-5">
                                <input x-model="columns" value="country" id="country" name="country" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="country" class="font-medium text-gray-700">País</label>
                            </div>
                        </div>

                        <div class="block px-4 py-2 relative flex">
                            <div class="flex items-center h-5">
                                <input x-model="columns" value="language" id="language" name="language" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="language" class="font-medium text-gray-700">Linguagem</label>
                            </div>
                        </div>

                        <div class="block px-4 py-2 relative flex">
                            <div class="flex items-center h-5">
                                <input x-model="columns" value="da" id="da" name="da" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="da" class="font-medium text-gray-700">DA</label>
                            </div>
                        </div>

                        <div class="block px-4 py-2 relative flex">
                            <div class="flex items-center h-5">
                                <input x-model="columns" value="dr" id="dr" name="dr" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="dr" class="font-medium text-gray-700">DR</label>
                            </div>
                        </div>

                        <div class="block px-4 py-2 relative flex">
                            <div class="flex items-center h-5">
                                <input x-model="columns" value="tf" id="tf" name="tf" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="tf" class="font-medium text-gray-700">TF</label>
                            </div>
                        </div>

                        <div class="block px-4 py-2 relative flex">
                            <div class="flex items-center h-5">
                                <input x-model="columns" value="category" id="category" name="category" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="category" class="font-medium text-gray-700">Categoria</label>
                            </div>
                        </div>

                        <div class="block px-4 py-2 relative flex">
                            <div class="flex items-center h-5">
                                <input x-model="columns" value="ssl" id="ssl" name="ssl" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="ssl" class="font-medium text-gray-700">SSL</label>
                            </div>
                        </div>

                        <div class="block px-4 py-2 relative flex">
                            <div class="flex items-center h-5">
                                <input x-model="columns" value="gambling" id="gambling" name="gambling" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="gambling" class="font-medium text-gray-700">Cassino</label>
                            </div>
                        </div>

                        <div class="block px-4 py-2 relative flex">
                            <div class="flex items-center h-5">
                                <input x-model="columns" value="sponsor" id="sponsor" name="sponsor" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="sponsor" class="font-medium text-gray-700">Publi</label>
                            </div>
                        </div>

                        <div class="block px-4 py-2 relative flex">
                            <div class="flex items-center h-5">
                                <input x-model="columns" value="cripto" id="cripto" name="cripto" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="cripto" class="font-medium text-gray-700">Cripto</label>
                            </div>
                        </div>

                    </x-slot>
                </x-dropdown>
            </div>

            @php
            $sort = request()->query('sort', 'url')
            @endphp

            <div class="overflow-x-scroll border border-gray-300 md:rounded-md">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th x-show="columns.includes('url')" scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900">
                                <a href="{{ route('sites.index', [ 'sort' => $sort == 'url' ? '-url' : 'url' ]) }}" class="group inline-flex cursor-pointer">
                                    Domínio

                                    <span class="{{ str_starts_with($sort, 'url') ? '' : 'rotate-180' }} {{ str_contains($sort, 'url') ? 'bg-gray-200 text-gray-900 group-hover:bg-gray-300' : 'invisible text-gray-400 group-hover:visible group-focus:visible' }} ml-2 flex-none rounded">
                                        <!-- Heroicon name: solid/chevron-down -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </a>
                            </th>

                            <th x-show="columns.includes('country')" scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                País
                            </th>

                            <th x-show="columns.includes('language')" scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Linguagem
                            </th>

                            <th x-show="columns.includes('da')" scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                <a href="{{ route('sites.index', [ 'sort' => $sort == 'da' ? '-da' : 'da' ]) }}" class="group inline-flex cursor-pointer">
                                    DA

                                    <span class="{{ str_starts_with($sort, 'da') ? '' : 'rotate-180' }} {{ str_contains($sort, 'da') ? 'bg-gray-200 text-gray-900 group-hover:bg-gray-300' : 'invisible text-gray-400 group-hover:visible group-focus:visible' }} ml-2 flex-none rounded">
                                        <!-- Heroicon name: solid/chevron-down -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </a>
                            </th>

                            <th x-show="columns.includes('dr')" scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                <a href="{{ route('sites.index', [ 'sort' => $sort == 'dr' ? '-dr' : 'dr' ]) }}" class="group inline-flex cursor-pointer">
                                    DR

                                    <span class="{{ str_starts_with($sort, 'dr') ? '' : 'rotate-180' }} {{ str_contains($sort, 'dr') ? 'bg-gray-200 text-gray-900 group-hover:bg-gray-300' : 'invisible text-gray-400 group-hover:visible group-focus:visible' }} ml-2 flex-none rounded">
                                        <!-- Heroicon name: solid/chevron-down -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </a>
                            </th>

                            <th x-show="columns.includes('tf')" scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                <a href="{{ route('sites.index', [ 'sort' => $sort == 'tf' ? '-tf' : 'tf' ]) }}" class="group inline-flex cursor-pointer">
                                    TF

                                    <span class="{{ str_starts_with($sort, 'tf') ? '' : 'rotate-180' }} {{ str_contains($sort, 'tf') ? 'bg-gray-200 text-gray-900 group-hover:bg-gray-300' : 'invisible text-gray-400 group-hover:visible group-focus:visible' }} ml-2 flex-none rounded">
                                        <!-- Heroicon name: solid/chevron-down -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </a>
                            </th>

                            <th x-show="columns.includes('category')" scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Categoria
                            </th>

                            <th x-show="columns.includes('ssl')" scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                SSL
                            </th>

                            <th x-show="columns.includes('gambling')" scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Cassino
                            </th>

                            <th x-show="columns.includes('sponsor')" scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Publi
                            </th>

                            <th x-show="columns.includes('cripto')" scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Cripto
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Preço
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">

                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">

                        <form action="{{ route('sites.index') }}">
                            <tr>
                                <td x-show="columns.includes('url')" class="whitespace-nowrap px-3 py-2">
                                    <input onchange="this.form.submit()" name="filter[url]" value="{{ optional(request()->query('filter'))['url'] }}" type="text" class="text-sm text-gray-500 placeholder:text-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Ex.: ocp" />
                                </td>

                                <td x-show="columns.includes('country')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    <select onchange="this.form.submit()" name="filter[country_id]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option {{ blank(optional(request()->query('filter'))['country_id']) ? 'selected' : '' }} value="">Todos</option>
                                        @foreach ($countries as $country)
                                        <option {{ optional(request()->query('filter'))['country_id'] == $country->id ? 'selected': '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td x-show="columns.includes('language')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    <select onchange="this.form.submit()" name="filter[language_id]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option {{ blank(optional(request()->query('filter'))['language_id']) ? 'selected' : '' }} value="">Todos</option>
                                        @foreach ($languages as $language)
                                        <option {{ optional(request()->query('filter'))['language_id'] == $language->id ? 'selected': '' }} value="{{ $language->id }}">{{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td x-show="columns.includes('da')" class="whitespace-nowrap px-3 py-2 border-l">
                                    <input onchange="this.form.submit()" name="filter[da]" value="{{ optional(request()->query('filter'))['da'] }}" type="text" class="text-sm text-gray-500 placeholder:text-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Ex.: > 1000" />
                                </td>

                                <td x-show="columns.includes('dr')" class="whitespace-nowrap px-3 py-2 border-l">
                                    <input onchange="this.form.submit()" name="filter[dr]" value="{{ optional(request()->query('filter'))['dr'] }}" type="text" class="text-sm text-gray-500 placeholder:text-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Ex.: < 5000" />
                                </td>

                                <td x-show="columns.includes('tf')" class="whitespace-nowrap px-3 py-2 border-l">
                                    <input onchange="this.form.submit()" name="filter[tf]" value="{{ optional(request()->query('filter'))['tf'] }}" type="text" class="text-sm text-gray-500 placeholder:text-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Ex.: > 1000" />
                                </td>

                                <td x-show="columns.includes('category')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    <select onchange="this.form.submit()" name="filter[category_id]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option {{ blank(optional(request()->query('filter'))['category_id']) ? 'selected' : '' }} value="">Todos</option>
                                        @foreach ($categories as $category)
                                        <option {{ optional(request()->query('filter'))['category_id'] == $category->id ? 'selected': '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td x-show="columns.includes('ssl')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    <select onchange="this.form.submit()" name="filter[ssl]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option {{ blank(optional(request()->query('filter'))['ssl']) ? 'selected' : '' }} value="">Todos</option>
                                        <option {{ optional(request()->query('filter'))['ssl'] == '1' ? 'selected' : '' }} value="1">Sim</option>
                                        <option {{ optional(request()->query('filter'))['ssl'] == '0' ? 'selected' : '' }} value="0">Não</option>
                                    </select>
                                </td>

                                <td x-show="columns.includes('gambling')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    <select onchange="this.form.submit()" name="filter[gambling]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option {{ blank(optional(request()->query('filter'))['gambling']) ? 'selected' : '' }} value="">Todos</option>
                                        <option {{ optional(request()->query('filter'))['gambling'] == '1' ? 'selected' : '' }} value="1">Sim</option>
                                        <option {{ optional(request()->query('filter'))['gambling'] == '0' ? 'selected' : '' }} value="0">Não</option>
                                    </select>
                                </td>

                                <td x-show="columns.includes('sponsor')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    <select onchange="this.form.submit()" name="filter[sponsor]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option {{ blank(optional(request()->query('filter'))['sponsor']) ? 'selected' : '' }} value="">Todos</option>
                                        <option {{ optional(request()->query('filter'))['sponsor'] == '1' ? 'selected' : '' }} value="1">Sim</option>
                                        <option {{ optional(request()->query('filter'))['sponsor'] == '0' ? 'selected' : '' }} value="0">Não</option>
                                    </select>
                                </td>

                                <td x-show="columns.includes('cripto')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    <select onchange="this.form.submit()" name="filter[cripto]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option {{ blank(optional(request()->query('filter'))['cripto']) ? 'selected' : '' }} value="">Todos</option>
                                        <option {{ optional(request()->query('filter'))['cripto'] == '1' ? 'selected' : '' }} value="1">Sim</option>
                                        <option {{ optional(request()->query('filter'))['cripto'] == '0' ? 'selected' : '' }} value="0">Não</option>
                                    </select>
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l"></td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l"></td>
                            </tr>
                        </form>

                        @foreach ($sites as $site)

                        <tr>
                            <td x-show="columns.includes('url')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                {{ $site->url }}
                            </td>

                            <td x-show="columns.includes('country')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ optional($site->country)->name ?? '-' }}
                            </td>

                            <td x-show="columns.includes('language')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ optional($site->language)->name ?? '-' }}
                            </td>

                            <td x-show="columns.includes('da')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ $site->da }}
                            </td>

                            <td x-show="columns.includes('dr')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ $site->dr }}
                            </td>

                            <td x-show="columns.includes('tf')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ $site->tf }}
                            </td>

                            <td x-show="columns.includes('category')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ optional($site->category)->name ?? '-' }}
                            </td>

                            <td x-show="columns.includes('ssl')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ $site->ssl ? 'Sim' : 'Não' }}
                            </td>

                            <td x-show="columns.includes('gambling')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ $site->gambling ? 'Sim' : 'Não' }}
                            </td>

                            <td x-show="columns.includes('sponsor')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ $site->sponsor ? 'Sim' : 'Não' }}
                            </td>

                            <td x-show="columns.includes('cripto')" class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ $site->cripto ? 'Sim' : 'Não' }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">

                            </td>

                            <td class="whitespace-nowrap px-3 py-2 text-sm border-l">
                                <div class="flex items-center justify-center">
                                    @if(!$site->trashed())
                                    <a href="{{ route('sites.edit', $site->id) }}" class="font-medium text-blue-600 hover:text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @endif

                                    <form action="{{ route('sites.toggle', $site->id) }}" method="POST" class="flex items-center">
                                        @csrf

                                        @if($site->trashed())
                                        <button type="submit" class="ml-2 font-medium text-green-500 hover:text-green-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        @else
                                        <button type="submit" class="ml-2 font-medium text-yellow-500 hover:text-yellow-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        @endif
                                    </form>

                                    @if($site->trashed())
                                    <form action="{{ route('sites.destroy', $site->id) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-2 font-medium text-red-600 hover:text-red-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $sites->links() }}
            </div>

        </div>
    </div>

    <script defer src="https://unpkg.com/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
</x-app-layout>