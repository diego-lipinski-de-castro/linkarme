<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Offers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="sm:px-6 lg:px-8">

            <div class="overflow-x-scroll border border-gray-300 md:rounded-md">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900">
                                Domínio
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Vendedor atual
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Preço atual
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Novo vendedor
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">
                                Novo preço
                            </th>

                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900 border-l">

                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">

                        @foreach ($offers as $offer)
                            <tr>
                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                    {{ $offer->site->url }}
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    {{ $offer->site->seller->name ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    {{ $offer->site->formatted_cost }}
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    {{ $offer->seller->name ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    {{ $offer->cost }}
                                </td>

                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 border-l">
                                    <div class="flex space-x-2 items-center justify-end">
                                        <form action="{{ route('offers.accept', $offer->id) }}" method="POST">
                                            @csrf
                                            <button class="px-2 py-1 font-medium rounded-md bg-green-500 text-white">Aprovar</button>
                                        </form>
                                        
                                        <form action="{{ route('offers.reject', $offer->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="px-2 py-1 font-medium rounded-md bg-red-500 text-white">Recusar</button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>