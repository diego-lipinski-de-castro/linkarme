<x-app-client-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sites') }}
            </h2>

            {{-- <a href="{{ route('client.sites.create') }}" class="inline-flex items-center px-4 px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Adicionar site</a> --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @php
            $sort = request()->query('sort', 'url')
            @endphp

            <div class="overflow-x-scroll border border-gray-300 md:rounded-md">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900">
                                <a href="{{ route('client.sites.index', [ 'sort' => $sort == 'url' ? '-url' : 'url' ]) }}" class="group inline-flex cursor-pointer">
                                    URL

                                    <span class="{{ str_starts_with($sort, 'url') ? '' : 'rotate-180' }} {{ str_contains($sort, 'url') ? 'bg-gray-200 text-gray-900 group-hover:bg-gray-300' : 'invisible text-gray-400 group-hover:visible group-focus:visible' }} ml-2 flex-none rounded">
                                        <!-- Heroicon name: solid/chevron-down -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </a>
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Categoria
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                <a href="{{ route('client.sites.index', [ 'sort' => $sort == 'da' ? '-da' : 'da' ]) }}" class="group inline-flex cursor-pointer">
                                    DA

                                    <span class="{{ str_starts_with($sort, 'da') ? '' : 'rotate-180' }} {{ str_contains($sort, 'da') ? 'bg-gray-200 text-gray-900 group-hover:bg-gray-300' : 'invisible text-gray-400 group-hover:visible group-focus:visible' }} ml-2 flex-none rounded">
                                        <!-- Heroicon name: solid/chevron-down -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </a>
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                <a href="{{ route('client.sites.index', [ 'sort' => $sort == 'dr' ? '-dr' : 'dr' ]) }}" class="group inline-flex cursor-pointer">
                                    DR

                                    <span class="{{ str_starts_with($sort, 'dr') ? '' : 'rotate-180' }} {{ str_contains($sort, 'dr') ? 'bg-gray-200 text-gray-900 group-hover:bg-gray-300' : 'invisible text-gray-400 group-hover:visible group-focus:visible' }} ml-2 flex-none rounded">
                                        <!-- Heroicon name: solid/chevron-down -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </a>
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                <a href="{{ route('client.sites.index', [ 'sort' => $sort == 'traffic' ? '-traffic' : 'traffic' ]) }}" class="group inline-flex cursor-pointer">
                                    Tráfego

                                    <span class="{{ str_starts_with($sort, 'traffic') ? '' : 'rotate-180' }} {{ str_contains($sort, 'traffic') ? 'bg-gray-200 text-gray-900 group-hover:bg-gray-300' : 'invisible text-gray-400 group-hover:visible group-focus:visible' }} ml-2 flex-none rounded">
                                        <!-- Heroicon name: solid/chevron-down -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </a>
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                <a href="{{ route('client.sites.index', [ 'sort' => $sort == 'tf' ? '-tf' : 'tf' ]) }}" class="group inline-flex cursor-pointer">
                                    TF

                                    <span class="{{ str_starts_with($sort, 'tf') ? '' : 'rotate-180' }} {{ str_contains($sort, 'tf') ? 'bg-gray-200 text-gray-900 group-hover:bg-gray-300' : 'invisible text-gray-400 group-hover:visible group-focus:visible' }} ml-2 flex-none rounded">
                                        <!-- Heroicon name: solid/chevron-down -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </a>
                            </th>

                            <th scope="col" class="relative whitespace-nowrap px-3 py-3 pl-3 pr-4 border-l">
                                <span class="sr-only">Favorite</span>
                            </th>

                            <!-- <th scope="col" class="relative whitespace-nowrap px-3 py-3 pl-3 pr-4 border-l">
                                <span class="sr-only">Ações</span>
                            </th> -->
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">

                        <form action="{{ route('client.sites.index') }}">
                            <tr>
                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                    <input onchange="this.form.submit()" name="filter[url]" value="{{ optional(request()->query('filter'))['url'] }}" type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Ex.: ocp" />
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    <select onchange="this.form.submit()" name="filter[category_id]" value="{{ optional(request()->query('filter'))['url'] }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option {{ blank(optional(request()->query('filter'))['category_id']) ? 'selected' : '' }} value="">Todos</option>
                                        @foreach ($categories as $category)
                                        <option {{ optional(request()->query('filter'))['category_id'] == $category->id ? 'selected': '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">

                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">

                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">

                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">

                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">

                                    <div>
                                        <input name="filter[favorites]" onchange="this.form.submit()" @if(optional(request()->query('filter'))['favorites']) checked @endif class="hidden" id="favorites" type="checkbox" />
                                        <label for="favorites">
                                            <span>
                                                @if(optional(request()->query('filter'))['favorites'])
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                                </svg>
                                                @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                                @endif
                                            </span>
                                        </label>
                                    </div>

                                    {{-- @if(optional(request()->query('filter'))['favorites'])
                                    <a href="{{ route('client.sites.index', array_merge(request()->all())) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                    </svg>
                                    </a>
                                    @else
                                    <a href="{{ route('client.sites.index', array_merge(request()->all(), ['filter[favorites]' => auth()->id()])) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </a>
                                    @endif
                                    --}}
                                </td>

                                <!-- <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">

                                </td> -->
                            </tr>
                        </form>

                        @foreach ($sites as $site)

                        <tr>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                {{ $site->url }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ optional($site->category)->name ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ $site->da }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ $site->dr }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ $site->traffic }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                {{ $site->tf }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                <form method="POST" action="{{ route('client.sites.favorite', $site->id) }}">
                                    @csrf

                                    <button>
                                        @if(in_array($site->id, $favorites))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                        </svg>
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        @endif
                                    </button>
                                </form>
                            </td>

                            {{-- <td class="flex relative whitespace-nowrap px-3 py-2 text-sm border-l">
                                    <a href="{{ route('client.sites.edit', $site->id) }}" class="font-medium text-blue-600 hover:text-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            </a>

                            <form action="{{ route('client.sites.destroy', $site->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-2 font-medium text-red-600 hover:text-red-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            </td> --}}
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
</x-app-client-layout>