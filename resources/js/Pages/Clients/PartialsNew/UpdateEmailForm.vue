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
    notify_updates_via_email: props.user.notify_updates_via_email,
});

const updateProfileInformation = () => {
    form.post(route('clients.update', props.user.id), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
    });
};
</script>

<template>
    <form @submit.prevent="updateProfileInformation" class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-6">
        
        <!-- Email -->
        <div>
            <InputLabel for="email" :value="$t('Email')" />
            <TextInput
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full"
            />
            <InputError :message="form.errors.email" class="mt-2" />

            <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                <p class="text-sm mt-2">
                    Your email address is unverified.

                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-gray-600 hover:text-gray-900"
                        @click.prevent="sendEmailVerification"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                    A new verification link has been sent to your email address.
                </div>
            </div>
        </div>

        <div>
            <InputLabel for="phone" :value="$t('Phone')" />
            <TextInput
                id="phone"
                v-model="form.phone"
                type="phone"
                class="mt-1 block w-full"
            />
            <InputError :message="form.errors.phone" class="mt-2" />
        </div>

        <div class="col-span-2">
            <InputLabel for="notify_updates_via_email" :value="$t('E-mail notifications')"/>
            <div class="mt-1">

                <select v-model="form.notify_updates_via_email" id="notify_updates_via_email" name="notify_updates_via_email" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option :value="null">Disabled</option>
                    <option value="DAILY">Daily</option>
                    <option value="WEEKLY">Weekly</option>
                </select>

            </div>

            <InputError class="mt-2" :message="form.errors.locale"/>
        </div>

        <div class="flex">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>

            <ActionMessage :on="form.recentlySuccessful" class="self-end ml-3">
                Saved.
            </ActionMessage>
        </div>
    </form>
</template>
