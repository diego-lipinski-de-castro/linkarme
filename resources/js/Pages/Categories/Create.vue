<script setup>
import AppLayout from '@/Layouts/AppLayoutNew.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, ref, watch } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route('categories.store'));
}

</script>
        
<template>
    <AppSuspense><AppLayout :title="$t('Add category')">
        <template #uppermenu>
            
        </template>

        <template #header>
            <div
                class="flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $t('Add category') }}
                </h2>
            </div>
        </template>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <form @submit.prevent="submit" class="flex flex-col space-y-6 p-6 bg-white border-b border-gray-200">
                
                <div>
                    <InputLabel for="name" :value="$t('Name')" />
                    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="flex justify-end">
                    <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit"
                        class="flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">{{ $t('Save') }}</button>
                </div>
            </form>
        </div>
    </AppLayout></AppSuspense>
</template>
        