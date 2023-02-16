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
});

const updateProfileInformation = () => {
    form.post(route('client.profile-information.update'), {
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
