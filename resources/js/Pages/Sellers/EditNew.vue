<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, ref, watch } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {
    EyeIcon,
    EyeSlashIcon,
} from '@heroicons/vue/20/solid'
import AppSuspense from '../../Layouts/AppSuspense.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import { UserCircleIcon } from '@heroicons/vue/24/outline';

const { seller } = defineProps({
    seller: Object,
})

const showPassword = ref(false)

const form = useForm({
    name: seller.name,
    email: seller.email,
    password: '',
    phone: seller.phone,
});

const submit = () => {
    form.put(route('sellers.update', seller.id));
}

const generatePassword = () => {
    form.password = Math.random().toString(36).slice(-8);
}

</script>
        
<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Edit seller')">


            <div class="max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                <div class="flex items-center space-x-2 px-5">
                    <div class="w-10">
                        <UserCircleIcon class="h-8 w-8" />
                    </div>

                    <div>
                        <span class="block font-bold">{{ $t('Add seller') }}</span>
                    </div>
                </div>

                <hr class="my-5">

                <div class="px-5 py-5">
                    <div class="ml-12 mr-16">
                        <form @submit.prevent="submit" class="grid grid-cols-1 gap-x-12 gap-y-6">
                            <!-- Name -->
                            <div>
                                <InputLabel for="name" :value="$t('Username')" />
                                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full"
                                    autocomplete="name" />
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="email" :value="$t('Email')" />
                                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" />
                                <InputError :message="form.errors.email" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="password" value="Password" />

                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <TextInput id="password" ref="passwordInput" v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        class="block w-full rounded-none rounded-l-md" autocomplete="password" />

                                    <button @click="showPassword = !showPassword" type="button"
                                        class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <EyeIcon v-if="!showPassword" class="h-6 w-6" />
                                        <EyeSlashIcon v-else class="h-6 w-6" />
                                    </button>

                                    <button @click="generatePassword" type="button"
                                        class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">{{
                                            $t('Generate') }}</button>
                                </div>

                                <InputError :message="form.errors.password" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="phone" :value="$t('Phone')" />
                                <TextInput id="phone" v-model="form.phone" type="phone" class="mt-1 block w-full" />
                                <InputError :message="form.errors.phone" class="mt-2" />
                            </div>

                            <div class="self-end mb-1 flex">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Save
                                </PrimaryButton>

                                <ActionMessage :on="form.recentlySuccessful" class="self-end ml-3">
                                    Saved.
                                </ActionMessage>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayoutNew>
    </AppSuspense>
</template>
        