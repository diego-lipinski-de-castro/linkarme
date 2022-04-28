<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categorias') }}
            </h2>

            <a href="{{ route('categories.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Adicionar
                categoria</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="overflow-hidden border border-gray-300 md:rounded-md">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-sm font-semibold text-gray-900">
                                Nome
                            </th>
                            
                            <th scope="col" class="relative whitespace-nowrap px-3 py-3">
                                <span class="sr-only">Ações</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">

                        @foreach ($categories as $category)
                            <tr>
                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                    {{ $category->name }}
                                </td>

                                <td class="flex relative whitespace-nowrap px-3 py-2 justify-end text-sm">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="font-medium text-blue-600 hover:text-blue-900">Editar</a>

                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-2 font-medium text-red-600 hover:text-red-900">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $categories->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
