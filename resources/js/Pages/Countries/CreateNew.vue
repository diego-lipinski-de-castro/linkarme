<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import { PlusIcon } from '@heroicons/vue/24/outline';

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route('countries.store'));
}

</script>
        
<template>
    <AppSuspense><AppLayoutNew :title="$t('Add country')">
        <div class="max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
            <div class="flex items-center space-x-2 px-5">
                <div class="w-10">
                    <PlusIcon class="h-6 w-6" />
                </div>

                <div>
                    <span class="block font-bold">{{ $t('Add country') }}</span>
                </div>
            </div>

            <hr class="my-5">

            <div class="px-5 py-5">
                <div class="ml-12 mr-16">
                    <form @submit.prevent="submit" class="grid grid-cols-1 gap-x-12 gap-y-6">
                        <!-- Name -->
                        <div>
                            <InputLabel for="name" :value="$t('Name')" />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full"
                                autocomplete="name" />
                            <InputError :message="form.errors.name" class="mt-2" />
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
    </AppLayoutNew></AppSuspense>
</template>
        