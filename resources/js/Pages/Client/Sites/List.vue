<script setup>
import ClientLayoutNew from '@/Layouts/ClientLayoutNew.vue';
import TableSortButton from '@/Components/TableSortButton.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, onMounted, reactive, ref, watch } from 'vue'
import unionBy from 'lodash/unionBy'
import {
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    ChevronUpIcon,
    MagnifyingGlassIcon,
    HomeIcon,
} from '@heroicons/vue/20/solid'
import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from '@headlessui/vue'
import { useTranslation } from "i18next-vue";
import { useCoinStore } from '@/stores/coin'
import AppSuspense from '../../../Layouts/AppSuspense.vue';

const coinStore = useCoinStore()
const { t } = useTranslation();

const props = defineProps({
    title: String,
    sites: Object,
    coins: Object,
    favorites: Array,
    interests: Array,
    filters: Object,
    project: Object,
});

const links = computed(() => {
    const _links = props.sites.links
    _links.shift()
    _links.pop()
    return _links
})

const _defaultColumns = [
    { key: 'sale', label: t('Price'), visible: true },
    { key: 'url', label: t('Domain'), visible: true },
    { key: 'da', label: t('DA'), visible: true },
    { key: 'dr', label: t('DR'), visible: true },
    { key: 'gambling', label: t('Accepts gambling'), visible: true },
    { key: 'sponsor', label: t('Marked as sponsored'), visible: true },
    { key: 'cripto', label: t('Cripto'), visible: false },
    { key: 'ssl', label: t('SSL'), visible: false },
    { key: 'category', label: t('Category'), visible: false },
    { key: 'obs', label: t('Obs'), visible: true },
    { key: 'example', label: t('Example'), visible: false },
    { key: 'inserted_at', label: t('Upload date'), visible: false },
];

const _columns =
    localStorage.getItem('client.sites.index.columns') ?
        unionBy(JSON.parse(localStorage.getItem('client.sites.index.columns')), _defaultColumns, 'key')
        : _defaultColumns

const columns = ref(_columns)

watch(columns, (n, o) => {
    localStorage.setItem('client.sites.index.columns', JSON.stringify(columns.value))
}, {
    deep: true,
})

const sort = ref(props.filters.sort)

const filters = reactive({
    favorites: props.filters.filter.favorites,
    interests: props.filters.filter.interests,
    project: props.filters.filter.project,
})

watch(sort, (n, o) => get());

const get = async () => {
    Inertia.get(route('client.sites.list'), {
        sort: sort.value,
        filter: {
            ...(filters.favorites && {
                favorites: filters.favorites,
            }),
            ...(filters.interests && {
                interests: filters.interests,
            }),
            ...(filters.project && {
                project: filters.project,
            }),
        },
    }, {
        preserveState: true,
    })
}

const toggleFavorite = async (site) => {
    Inertia.post(route('client.sites.favorite', site), null, {
        preserveScroll: true,
    })
}

const toggleInterest = async (site) => {
    Inertia.post(route('client.sites.interest', site), null, {
        preserveScroll: true,
    })
}

onMounted(() => {
    setTimeout(() => {
        tippy('[data-tippy-content]');
    }, 100)
})

const content = computed(() => {
    if(filters.favorites) {
        return {
            title: t('Your favorites'),
            subtitle: t('The best of the best'),
            stepone: t('Your account'),
            steptwo: t('Favorites'),
        }
    }

    if(filters.interests) {
        return {
            title: t('Your interests'),
            subtitle: t('Websites that you saved to take a look later'),
            stepone: t('Your account'),
            steptwo: t('Interest list'),
        }
    }

    if(filters.hasOwnProperty('project')) {
        return {
            title: props.project.name,
            subtitle: t('Project collection'),
            stepone: t('Projects'),
            steptwo: props.project.name,
        }
    }

    return {
        title: '',
        subtitle: '',
        stepone: '',
        steptwo: '',
    }
})
</script>

<template>
    <AppSuspense>
        <ClientLayoutNew title="Sites">
            <div class="rounded-md bg-white px-5 py-4 shadow sm:px-6">
                <div class="flex flex-col min-h-48">

                    <nav class="flex">
                        <ol role="list" class="flex items-center space-x-4">
                            <li>
                                <div>
                                    <a href="#" class="text-gray-400 hover:text-gray-500 transition-colors">
                                        <HomeIcon class="h-5 w-5 flex-shrink-0" aria-hidden="true" />
                                        <span class="sr-only">Home</span>
                                    </a>
                                </div>
                            </li>

                            <li>
                                <div class="flex items-center">
                                    <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                                    <a href="#" class="ml-4 text-sm text-gray-400 hover:text-gray-500 transition-colors">{{ content.stepone }}</a>
                                </div>
                            </li>

                            <li>
                                <div class="flex items-center">
                                    <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                                    <a href="#" class="ml-4 text-sm text-gray-400 hover:text-gray-500 transition-colors">{{ content.steptwo }}</a>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <hr class="my-4">

                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ content.title }}</h2>
                        <span class="text-sm text-gray-400">{{ content.subtitle }}</span>
                    </div>

                </div>
            </div>

            <div class="mt-5 hidden sm:block">
                    <div class="flex flex-col">

                        <div>
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{
                                $t('Showing results:')
                            }}&nbsp;{{ sites.total }}&nbsp;{{ $t('sites found') }}</h2>
                        </div>

                        <hr class="my-5">

                        <div class="flex flex-col">
                            <span class="font-bold text-sm whitespace-nowrap">{{ $t('Column filter:') }}</span>
                            <div class="mt-2 flex flex-wrap">
                                <div v-for="(column, index) in columns" :key="index" class="px-4 py-2 relative flex">
                                    <div class="flex items-center h-5">
                                        <input v-model="column.visible" :value="column.key" :id="column.key"
                                            :name="column.key" type="checkbox"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" />
                                    </div>

                                    <div class="ml-2 text-sm">
                                        <label :for="column.key" class="font-medium text-gray-700 whitespace-nowrap">{{
                                            column.label
                                        }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="mt-5 min-w-full overflow-hidden overflow-x-auto align-middle border border-gray-200 sm:rounded-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th v-show="columns[0].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Price') }}</span>
                                                <TableSortButton column='sale' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>

                                        <th v-show="columns[1].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Domain') }}</span>
                                                <TableSortButton column='url' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[2].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('DA') }}</span>
                                                <TableSortButton column='da' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>

                                        <th v-show="columns[3].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('DR') }}</span>
                                                <TableSortButton column='dr' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[4].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Accepts gambling') }}</th>
                                        <th v-show="columns[5].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Marked as sponsored') }}</th>
                                        <th v-show="columns[6].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Cripto') }}</th>
                                        <th v-show="columns[7].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('SSL') }}</th>
                                        <th v-show="columns[8].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Category') }}</th>
                                        <!-- <th v-show="columns[9].visible"
                                        class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{$t('Banners')}}</th>
                                    <th v-show="columns[10].visible"
                                        class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">Links {{$t('menu')}}</th> -->
                                        <th v-show="columns[9].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Obs') }}</th>
                                        <th v-show="columns[10].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Example') }}</span>
                                                <TableSortButton column='example' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[11].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Upload date') }}</span>
                                                <TableSortButton column='inserted_at' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Add to favorites') }}</th>
                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Add to interest list') }}</th>
                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Add to a project') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="(site, index) in sites.data" :key="index" class="bg-white">
                                        <td v-show="columns[0].visible" class="whitespace-nowrap px-4 py-4 text-sm">
                                            <span class="relative flex space-x-2 items-center">
                                                <span>
                                                    {{ site.sale_coin != coinStore.coin ? '~ ' : null }}
                                                    {{
                                                        $filters.currency((Math.round((site.sale /
                                                            coinStore.ratios[site.sale_coin]) / 5) * 5) / 100,
                                                            coins[coinStore.coin])
                                                    }}
                                                </span>

                                                <!-- <span class="text-red-500">{{ $filters.currency(site.sale / 100, coins[site.sale_coin]) }} - {{ site.sale }} - {{ site.sale_coin }}</span> -->
                                            </span>
                                        </td>
                                        <td v-show="columns[1].visible" class="whitespace-nowrap px-4 py-4 text-sm">
                                            <Link :href="route('client.sites.show', site.id)"
                                                class="text-gray-500 hover:text-gray-900">
                                            {{ site.url }}
                                            </Link>
                                        </td>
                                        <td v-show="columns[2].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.da ?? '-' }}
                                        </td>
                                        <td v-show="columns[3].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.dr ?? '-' }}
                                        </td>
                                        <td v-show="columns[4].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ $t(site.gambling ? 'Yes' : 'No') }}
                                        </td>
                                        <td v-show="columns[5].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ $t(site.sponsor ? 'Yes' : 'No') }}
                                        </td>
                                        <td v-show="columns[6].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ $t(site.cripto ? 'Yes' : 'No') }}
                                        </td>
                                        <td v-show="columns[7].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ $t(site.ssl ? 'Yes' : 'No') }}
                                        </td>
                                        <td v-show="columns[8].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            <span :data-tippy-content="site.category?.subtitle">{{ site.category?.title ??
                                                '-' }}</span>
                                        </td>
                                        <!-- <td v-show="columns[9].visible"
                                        class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                        {{ site.banner ? 'Sim' : 'Não' }}
                                    </td>
                                    <td v-show="columns[10].visible"
                                        class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                        {{ site.menu ? 'Sim' : 'Não' }}
                                    </td> -->
                                        <td v-show="columns[9].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.obs ?? '-' }}
                                        </td>
                                        <td v-show="columns[10].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            -
                                        </td>
                                        <td v-show="columns[11].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.formatted_inserted_at }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500">
                                            <button @click="toggleFavorite(site.id)">
                                                <svg v-if="favorites.includes(site.id)" xmlns="http://www.w3.org/2000/svg"
                                                    class="text-red-500 h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                        clip-rule="evenodd" />
                                                </svg>

                                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                            </button>
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-500">
                                            <button @click="toggleInterest(site.id)">
                                                <svg v-if="interests.includes(site.id)" xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor" viewBox="0 0 24 24"
                                                    class="text-blue-500 w-[22px] h-[22px]">
                                                    <path fill-rule="evenodd"
                                                        d="M3 2.25a.75.75 0 01.75.75v.54l1.838-.46a9.75 9.75 0 016.725.738l.108.054a8.25 8.25 0 005.58.652l3.109-.732a.75.75 0 01.917.81 47.784 47.784 0 00.005 10.337.75.75 0 01-.574.812l-3.114.733a9.75 9.75 0 01-6.594-.77l-.108-.054a8.25 8.25 0 00-5.69-.625l-2.202.55V21a.75.75 0 01-1.5 0V3A.75.75 0 013 2.25z"
                                                        clip-rule="evenodd" />
                                                </svg>

                                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[22px]">
                                                    <path fill-rule="evenodd"
                                                        d="M3 2.25a.75.75 0 01.75.75v.54l1.838-.46a9.75 9.75 0 016.725.738l.108.054a8.25 8.25 0 005.58.652l3.109-.732a.75.75 0 01.917.81 47.784 47.784 0 00.005 10.337.75.75 0 01-.574.812l-3.114.733a9.75 9.75 0 01-6.594-.77l-.108-.054a8.25 8.25 0 00-5.69-.625l-2.202.55V21a.75.75 0 01-1.5 0V3A.75.75 0 013 2.25z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-500">

                                            <!-- <Menu as="div" class="h-full relative flex-shrink-0">
                                                <MenuButton
                                                    class="inline-flex h-full w-8 items-center justify-center rounded-full bg-white bg-transparent focus:outline-none text-gray-700 transition-transform hover:scale-110">
                                                    <span class="sr-only">Open projects</span>
                                                    <PlusCircleIcon class="h-6 w-6" />
                                                </MenuButton>

                                                <transition enter-active-class="transition ease-out duration-100"
                                                    enter-from-class="transform opacity-0 scale-95"
                                                    enter-to-class="transform opacity-100 scale-100"
                                                    leave-active-class="transition ease-in duration-75"
                                                    leave-from-class="transform opacity-100 scale-100"
                                                    leave-to-class="transform opacity-0 scale-95">
                                                    <MenuItems
                                                        class="absolute right-0 z-10 mt-0 origin-top-right rounded-md overflow-hidden bg-white border border-gray-300 border-opacity-50 shadow-sm focus:outline-none">
                                                        <MenuItem v-for="(project, index) in projects" v-slot="{ active }">
                                                            <button :class="[active ? 'bg-gray-100' : '', 'w-full flex items-center whitespace-nowrap space-x-4 py-2 px-4 text-sm text-gray-500']">
                                                                <span :style="{ 'background-color': project.color }" class="h-2 w-2 rounded-full"></span>
                                                                <span>{{ project.name }}</span>
                                                            </button>
                                                        </MenuItem>
                                                    </MenuItems>
                                                </transition>
                                            </Menu> -->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <nav class="mt-6 flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                            <div class="-mt-px flex w-0 flex-1">
                                <Link :href="sites.prev_page_url"
                                    class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                <ArrowLongLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                                {{ $t('Previous') }}
                                </Link>
                            </div>
                            <div class="hidden md:-mt-px md:flex">
                                <Link v-for="(link, index) in links" :key="index" :href="link.url"
                                    :class="[link.active ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'inline-flex items-center border-t-2  px-4 pt-4 text-sm font-medium ']">
                                {{ link.label }}
                                </Link>
                            </div>
                            <div class="-mt-px flex w-0 flex-1 justify-end">
                                <Link :href="sites.next_page_url"
                                    class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                {{ $t('Next') }}
                            <ArrowLongRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                            </Link>
                        </div>
                    </nav>
                </div>
        </div>
    </ClientLayoutNew>
</AppSuspense></template>
