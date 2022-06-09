<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Importar sites') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="sm:px-6 lg:px-8">

            @if(session('diff'))
            <span class="px-3 py-2 border border-green-300 rounded-md bg-green-100 text-green-500 block mb-3">
                {{ session('diff') }} novos sites foram adicionados.
            </span>
            @endif

            @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('failures') && count(session('failures')) > 0)

            <div class="mb-3 overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-md">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 sm:pl-6">Linha</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 border-l border-gray-300">Coluna</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 border-l border-gray-300">Valor</th>
                            <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 border-l border-gray-300">Erro</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach (session('failures') as $failure)
                            <tr>
                                <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 sm:pl-6">{{ $failure['row'] }}</td>
                                <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 border-l border-gray-300">{{ $failure['attribute'] }}</td>
                                <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 border-l border-gray-300">{{ $failure['values'][$failure['attribute']] }}</td>
                                <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 border-l border-gray-300">{{ $failure['errors'][0] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @endif

            <form method="POST" action="{{ route('sites.importSubmit') }}" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col">
                    <input id="file" name="file" type="file">
                    <button class="mt-3 w-min inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Enviar</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>