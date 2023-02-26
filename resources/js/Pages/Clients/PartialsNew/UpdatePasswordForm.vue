<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/20/solid';

const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const showPassword = ref(false)

const generatePassword = () => {
    form.password = Math.random().toString(36).slice(-8);
}

const updatePassword = () => {
    form.put(route('clients.update', props.user.id), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password');
                passwordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <form @submit.prevent="updatePassword" class="grid grid-cols-1 gap-x-12 gap-y-6">
        <div>
            <InputLabel for="password" value="Password" />
            
            <div class="mt-1 flex rounded-md shadow-sm">
                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    class="block w-full rounded-none rounded-l-md"
                    autocomplete="password"
                />

                <button @click="showPassword = !showPassword" type="button" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    <EyeIcon v-if="!showPassword" class="h-6 w-6"/>
                    <EyeSlashIcon v-else class="h-6 w-6"/>
                </button>

                <button @click="generatePassword" type="button" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">{{ $t('Generate') }}</button>
            </div>

            <InputError :message="form.errors.password" class="mt-2" />
        </div>

        <div class="flex">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>

            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </ActionMessage>
        </div>
    </form>
</template>
