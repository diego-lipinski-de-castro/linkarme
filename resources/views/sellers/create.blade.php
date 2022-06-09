<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Adicionar vendedor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('sellers.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                                <div class="mt-1">
                                    <input type="text" name="name" id="name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 @enderror" placeholder="João" />
                                </div>

                                @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                                <div class="mt-1">
                                    <input type="email" name="email" id="email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 @enderror" placeholder="joao@linkarme.com" />
                                </div>

                                @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <div class="w-full relative flex items-stretch flex-grow focus-within:z-10">
                                        <input id="password-input" type="password" name="password" id="password" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300 @error('password') border-red-300 @enderror" />
                                    </div>
                                    <button id="toggle-password-field" type="button" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>

                                        <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>

                                    <button id="generate-password" type="button" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <span>Gerar</span>
                                    </button>
                                </div>

                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Whatsapp</label>
                                <div class="mt-1">
                                    <input type="text" name="phone" id="phone" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('phone') border-red-300 @enderror" placeholder="João" />
                                </div>

                                @error('phone')
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

    <script>
        const togglepwdbtn = document.getElementById('toggle-password-field');
        const eyeicon = document.getElementById('eye-icon');
        const eyeofficon = document.getElementById('eye-off-icon');

        const genpwdbtn = document.getElementById('generate-password');
        const pwdinput = document.getElementById('password-input');

        togglepwdbtn.addEventListener('click', () => {
            pwdinput.type = pwdinput.type == 'password' ? 'text' : 'password';

            if(pwdinput.type == 'password') {
                eyeicon.classList.remove('hidden');
                eyeofficon.classList.add('hidden');
            } else {
                eyeicon.classList.add('hidden');
                eyeofficon.classList.remove('hidden');
            }
        });

        genpwdbtn.addEventListener('click', () => {
            pwdinput.value = Math.random().toString(36).slice(-8);
        });
    </script>

</x-app-layout>