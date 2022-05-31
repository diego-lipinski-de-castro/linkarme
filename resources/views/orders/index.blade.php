<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pedidos') }}
            </h2>

            <a href="{{ route('orders.create') }}" class="inline-flex items-center px-4 px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Adicionar pedido</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="overflow-x-scroll border border-gray-300 md:rounded-md">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <!-- <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900">
                                Site
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Cliente
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Vendedor
                            </th> -->

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                URL
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Status
                            </th>
                            
                            <th scope="col" class="relative whitespace-nowrap px-3 py-3 pl-3 pr-4 border-l">
                                <span class="sr-only">Ações</span>
                            </th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-200 bg-white">

                        <form action="{{ route('orders.index') }}">
                            <tr>
                                {{--
                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                        <select onchange="this.form.submit()" name="filter[site_id]"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            >
                                            <option {{ blank(optional(request()->query('filter'))['site_id']) ? 'selected' : '' }} value="">Todos</option>
                                            @foreach ($sites as $site)
                                                <option {{ optional(request()->query('filter'))['site_id'] == $site->id ? 'selected': '' }} value="{{ $site->id }}">{{ $site->url }}</option>
                                            @endforeach
                                        </select>
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                        <select onchange="this.form.submit()" name="filter[client_id]"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            >
                                            <option {{ blank(optional(request()->query('filter'))['client_id']) ? 'selected' : '' }} value="">Todos</option>
                                            @foreach ($clients as $client)
                                                <option {{ optional(request()->query('filter'))['client_id'] == $client->id ? 'selected': '' }} value="{{ $client->id }}">{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                        <select onchange="this.form.submit()" name="filter[seller_id]"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            >
                                            <option {{ blank(optional(request()->query('filter'))['seller_id']) ? 'selected' : '' }} value="">Todos</option>
                                            @foreach ($sellers as $seller)
                                                <option {{ optional(request()->query('filter'))['seller_id'] == $seller->id ? 'selected': '' }} value="{{ $seller->id }}">{{ $seller->name }}</option>
                                            @endforeach
                                        </select>
                                </td>
                                --}}

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    <input onchange="this.form.submit()" name="filter[url]" value="{{ optional(request()->query('filter'))['url'] }}"
                                        type="text"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" />
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                        <select onchange="this.form.submit()" name="filter[status]"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            >
                                            <option {{ blank(optional(request()->query('filter'))['status']) ? 'selected' : '' }} value="">Todos</option>
                                            @foreach ($statuses as $key => $label)
                                                <option {{ optional(request()->query('filter'))['status'] == $key ? 'selected': '' }} value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">

                                </td>
                            </tr>
                        </form>

                        @foreach ($orders as $order)

                            <tr>
                                {{--
                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                    {{ $order->site->url }}
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    {{ $order->client->name }}
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    {{ $order->seller->name }}
                                </td>
                                --}}

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    {{ $order->url }}
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    {{ $order->formatted_status }}
                                </td>

                                <td class="flex relative whitespace-nowrap px-3 py-2 text-sm border-l">
                                    <a href="{{ route('orders.edit', $order->id) }}" class="font-medium text-blue-600 hover:text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-2 font-medium text-red-600 hover:text-red-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
