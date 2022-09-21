<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { ref } from 'vue'
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import {
    ScaleIcon,
} from '@heroicons/vue/24/outline'
import {
    BanknotesIcon,
    BuildingOfficeIcon,
    CheckCircleIcon,
    ChevronRightIcon,
} from '@heroicons/vue/20/solid'

const props = defineProps({
    title: String,
    orders: Number,
    used: Array,
    unused: Array,
    new: Array,
    recommended: Array,
    favorites: Array,
});

const cards = [
    { name: 'Pedidos feitos', href: route('client.orders.index'), icon: ScaleIcon, amount: props.orders },
]

const list = [
    { label: 'Sites utilizados', sites: props.used },
    { label: 'Sites não utilizados', sites: props.unused },
    { label: 'Novos sites', sites: props.new },
    { label: 'Sites recomendados', sites: props.recommended },
];

const toggleFavorite = async (site) => {
    Inertia.post(route('client.sites.favorite', site), null, {
        preserveScroll: true,
    })
}

</script>
        
<template>
    <ClientLayout title="Dashboard">
        <template #header>
            <div class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:mx-auto lg:px-8">
                    <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center">
                                <img class="hidden h-16 w-16 rounded-full sm:block"
                                    src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2.6&w=256&h=256&q=80"
                                    alt="" />
                                <div>
                                    <div class="flex items-center">
                                        <img class="h-16 w-16 rounded-full sm:hidden"
                                            src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2.6&w=256&h=256&q=80"
                                            alt="" />
                                        <h1
                                            class="ml-3 text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:leading-9">
                                            Good morning, {{ $page.props.user.name }}</h1>
                                    </div>
                                    <dl class="mt-6 flex flex-col sm:ml-3 sm:mt-1 sm:flex-row sm:flex-wrap">
                                        <dt class="sr-only">Company</dt>
                                        <dd
                                            class="flex items-center text-sm font-medium capitalize text-gray-500 sm:mr-6">
                                            <BuildingOfficeIcon class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400"
                                                aria-hidden="true" />
                                            Duke street studio
                                        </dd>
                                        <dt class="sr-only">Account status</dt>
                                        <dd
                                            class="mt-3 flex items-center text-sm font-medium capitalize text-gray-500 sm:mr-6 sm:mt-0">
                                            <CheckCircleIcon class="mr-1.5 h-5 w-5 flex-shrink-0 text-green-400"
                                                aria-hidden="true" />
                                            Verified account
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                            <button type="button"
                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Add
                                money</button>
                            <button type="button"
                                class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Send
                                money</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div>
            <div>
                <h2 class="text-lg font-medium leading-6 text-gray-900">Overview</h2>
                <div class="mt-2 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Card -->
                    <div v-for="card in cards" :key="card.name" class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <component :is="card.icon" class="h-6 w-6 text-gray-400" aria-hidden="true" />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="truncate text-sm font-medium text-gray-500">{{ card.name }}</dt>
                                        <dd>
                                            <div class="text-lg font-medium text-gray-900">{{ card.amount }}</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <Link :href="card.href" class="font-medium text-cyan-700 hover:text-cyan-900">Ver todos</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-for="(item, index) in list" :key="index">
                <h2 class="mt-8 text-lg font-medium leading-6 text-gray-900">{{ item.label }}</h2>

                <div class="sm:hidden">
                    <ul role="list" class="mt-2 divide-y divide-gray-200 border rounded-md overflow-hidden">
                        <li v-for="(site, index) in item.sites" :key="index">
                        <Link :href="route('client.sites.edit', site.id)"
                            class="block bg-white px-4 py-4 hover:bg-gray-50">
                        <span class="flex items-center space-x-4">
                            <span class="flex flex-1 space-x-2 truncate">
                                <span class="text-sm text-gray-500">
                                    {{ site.url }}
                                </span>
                            </span>
                            <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                        </span>
                        </Link>
                    </li>
                    </ul>
                </div>

                <!-- Activity table (small breakpoint and up) -->
                <div class="hidden sm:block">
                    <div class="mt-2 flex flex-col">
                        <div class="min-w-full overflow-hidden overflow-x-auto align-middle shadow sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">Domínio
                                        </th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">DA
                                        </th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">DR
                                        </th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">Tráfego
                                        </th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">Cassino</th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">Publi</th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">Cripto</th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">SSL</th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">Categoria</th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">Banners</th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">Links menu</th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">Atualizado às
                                        </th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">Favorito</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="(site, index) in item.sites" :key="index" class="bg-white">
                                    <td  class="whitespace-nowrap px-6 py-4 text-sm">
                                        <Link :href="route('client.sites.edit', site.id)"
                                            class="text-gray-500 hover:text-gray-900">
                                        {{ site.url }}
                                        </Link>
                                    </td>
                                    <td 
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.da ?? '-' }}
                                    </td>
                                    <td 
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.dr ?? '-' }}
                                    </td>
                                    <td 
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.traffic ?? '-' }}
                                    </td>
                                    <td 
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <div class="flex justify-center">
                                            <span
                                                :class="['block h-2 w-2 rounded-full', site.gambling ? 'bg-green-300' : 'bg-red-300']"></span>
                                        </div>
                                    </td>
                                    <td 
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <div class="flex justify-center">
                                            <span
                                                :class="['block h-2 w-2 rounded-full', site.sponsor ? 'bg-green-300' : 'bg-red-300']"></span>
                                        </div>
                                    </td>
                                    <td 
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <div class="flex justify-center">
                                            <span
                                                :class="['block h-2 w-2 rounded-full', site.cripto ? 'bg-green-300' : 'bg-red-300']"></span>
                                        </div>
                                    </td>
                                    <td 
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <div class="flex justify-center">
                                            <span
                                                :class="['block h-2 w-2 rounded-full', site.ssl ? 'bg-green-300' : 'bg-red-300']"></span>
                                        </div>
                                    </td>
                                    <td 
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.category?.name ?? '-' }}
                                    </td>
                                    <td 
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.banner ? 'Sim' : 'Não' }}
                                    </td>
                                    <td 
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.menu ? 'Sim' : 'Não' }}
                                    </td>
                                    <td 
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.formatted_updated_at }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <button @click="toggleFavorite(site.id)">
                                            <svg v-if="favorites.includes(site.id)" xmlns="http://www.w3.org/2000/svg" class="text-red-500 h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                            </svg>

                                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ClientLayout>
</template>
        