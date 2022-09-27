<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { computed } from 'vue';
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
    usedCount: Number,
    favs: Array,
    unusedCount: Number,
    new: Array,
    recommended: Array,
    favorites: Array,
});

console.log(props.favs)

const cards = [
    { name: 'Pedidos feitos', href: route('client.orders.index'), icon: ScaleIcon, amount: props.orders },
    { name: 'Sites utilizados', href: route('client.sites.index'), icon: ScaleIcon, amount: props.usedCount },
    { name: 'Sites não utilizados', href: route('client.sites.index'), icon: ScaleIcon, amount: props.unusedCount },
]

const list = [
    { label: 'Favoritos', sites: props.favs, href: route('client.sites.index') },
    { label: 'Novos sites', sites: props.new, href: route('client.sites.index') },
    { label: 'Sites recomendados', sites: props.recommended, href: route('client.sites.index') },
];

const greeting = computed(() => {
    const hour = new Date().getHours();
    const welcomeTypes = ['Good morning', 'Good afternoon', 'Good evening'];
    let welcomeText = '';
    
    if (hour < 12) welcomeText = welcomeTypes[0];
    else if (hour < 18) welcomeText = welcomeTypes[1];
    else welcomeText = welcomeTypes[2];

    return welcomeText
})

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
                                    :src="$page.props.user.profile_photo_url"
                                    :alt="$page.props.user.name" />
                                <div>
                                    <div class="flex items-center">
                                        <img class="h-16 w-16 rounded-full sm:hidden"
                                            :src="$page.props.user.profile_photo_url"
                                            :alt="$page.props.user.name" />
                                        <h1
                                            class="ml-3 text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:leading-9">
                                            {{ greeting }}, {{ $page.props.user.name }}</h1>
                                    </div>
                                    <dl class="mt-6 flex flex-col sm:ml-3 sm:mt-1 sm:flex-row sm:flex-wrap">
                                        <dt v-if="false" class="sr-only">Company</dt>
                                        <dd v-if="false"
                                            class="flex items-center text-sm font-medium capitalize text-gray-500 sm:mr-6">
                                            <BuildingOfficeIcon class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400"
                                                aria-hidden="true" />
                                            Duke street studio
                                        </dd>
                                        <dt v-if="$page.props.user.full" class="sr-only">Account status</dt>
                                        <dd v-if="$page.props.user.full"
                                            class="mt-3 flex items-center text-sm font-medium capitalize text-gray-500 sm:mr-6 sm:mt-0">
                                            <CheckCircleIcon class="mr-1.5 h-5 w-5 flex-shrink-0 text-green-400"
                                                aria-hidden="true" />
                                            Verified account
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                            <button type="button"
                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Add
                                money</button>
                            <button type="button"
                                class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Send
                                money</button>
                        </div> -->
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
                            <Link :href="card.href" class="text-sm font-medium text-cyan-700 hover:text-cyan-900">Ver todos</Link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6">
                <div v-for="(item, index) in list" :key="index">
                    <h2 class="mt-8 text-lg font-medium leading-6 text-gray-900">{{ item.label }}</h2>

                    <div class="sm:hidden">
                        <ul role="list" class="mt-2 divide-y divide-gray-200 border rounded-md overflow-hidden">
                            <li v-if="item.sites.length == 0">
                                <span class="block bg-white px-4 py-4 text-center text-gray-500 italic">Em breve</span></li>
                            <li v-else v-for="(site, index) in item.sites" :key="index">
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
                                            <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                                scope="col">Domínio
                                            </th>
                                            <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                                scope="col">DA
                                            </th>
                                            <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                                scope="col">DR
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-if="item.sites.length == 0">
                                            <td colspan="3" class="text-center text-gray-500 italic py-2">Em breve</td>
                                        </tr>
                                        <tr v-else v-for="(site, index) in item.sites" :key="index" class="bg-white">
                                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                <Link :href="route('client.sites.edit', site.id)"
                                                    class="text-gray-500 hover:text-gray-900">
                                                {{ site.url }}
                                                </Link>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                {{ site.da ?? '-' }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                {{ site.dr ?? '-' }}
                                            </td>
                                        </tr>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="bg-gray-50 px-5 py-3">
                                                <Link :href="item.href" class="text-sm font-medium text-cyan-700 hover:text-cyan-900">Ver todos</Link>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ClientLayout>
</template>
        