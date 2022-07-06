<x-app-seller-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pedidos') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="sm:px-6 lg:px-8">

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

                                <td class="whitespace-nowrap px-3 py-2 text-sm border-l">
                                   
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
</x-app-seller-layout>
