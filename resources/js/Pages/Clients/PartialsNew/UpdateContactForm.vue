<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    name: props.user.name,
    birthday: props.user.birthday,
    email: props.user.email,
    photo: null,

    phone: props.user.phone,
    
    contact: props.user.contact,
    contact_where: props.user.contact_where,
    contact_id: props.user.contact_id,
    contact_how: props.user.contact_how,
});

const updateProfileInformation = () => {
    form.post(route('clients.update', props.user.id), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onError: (error) => console.log(error),
    });
};
</script>

<template>
    <form @submit.prevent="updateProfileInformation" class="grid grid-cols-1 sm:grid-cols-3 gap-x-12 gap-y-6">
        
        <!-- Email -->
        <div>
            <InputLabel for="contact" :value="$t('Best way to contact is...')" />

            <div class="mt-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-center">
                    <input type="radio" v-model="form.contact" value="email" />
                    <span class="ml-2 text-sm text-gray-600">{{ $t('Email') }}</span>
                </label>

                <label class="flex items-center">
                    <input type="radio" v-model="form.contact" value="call" />
                    <span class="ml-2 text-sm text-gray-600">{{ $t('Phone call') }}</span>
                </label>

                <label class="flex items-center">
                    <input type="radio" v-model="form.contact" value="message" />
                    <span class="ml-2 text-sm text-gray-600">{{ $t('Message app') }}</span>
                </label>

                <label class="flex items-center">
                    <input type="radio" v-model="form.contact" value="other" />
                    <span class="ml-2 text-sm text-gray-600">{{ $t('Other') }}</span>
                </label>
            </div>
        </div>

        <div class="col-span-2">
            <div class="grid col-span-1 sm:grid-col-span-2 gap-6">
                <div>
                    <InputLabel for="contact_where" :value="$t('Which one')" />
                    <TextInput
                        id="contact_where"
                        v-model="form.contact_where"
                        type="text"
                        class="mt-1 block w-full disabled:opacity-50"
                        :disabled="form.contact !== 'other'"
                    />
                    <InputError :message="form.errors.contact_where" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="contact_id" :value="$t('How to find (ID, username, @, number, etc)')" />
                    <TextInput
                        id="contact_id"
                        v-model="form.contact_id"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.contact_id" class="mt-2" />
                </div>

                <div class="col-span-2">
                    <InputLabel for="contact_how" :value="$t('How')" />
                    <textarea
                        id="contact_how"
                        v-model="form.contact_how"
                        class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"
                    />
                    <InputError :message="form.errors.contact_how" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="col-span-3 flex justify-end">
            <ActionMessage :on="form.recentlySuccessful" class="self-end mr-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </div>
    </form>
</template>
