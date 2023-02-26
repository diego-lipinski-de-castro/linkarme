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

    locale: props.user.locale ?? 'en',
    coin: props.user.coin ?? 'USD',
    full: props.user.full ?? false,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('clients.update', props.user.id), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    // Inertia.delete(route('client.current-user-photo.destroy'), {
    //     preserveScroll: true,
    //     onSuccess: () => {
    //         photoPreview.value = null;
    //         clearPhotoFileInput();
    //     },
    // });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <form @submit.prevent="updateProfileInformation" class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-6">
        <!-- First Name -->
        <div>
            <InputLabel for="first_name" :value="$t('First name')" />
            <TextInput id="first_name" v-model="form.first_name" type="text" class="mt-1 block w-full"
                autocomplete="first_name" />
            <InputError :message="form.errors.first_name" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div>
            <InputLabel for="last_name" :value="$t('Last name')" />
            <TextInput id="last_name" v-model="form.last_name" type="text" class="mt-1 block w-full"
                autocomplete="last_name" />
            <InputError :message="form.errors.last_name" class="mt-2" />
        </div>


        <!-- Name -->
        <div>
            <InputLabel for="name" :value="$t('Username (optional)')" />
            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" autocomplete="name" />
            <InputError :message="form.errors.name" class="mt-2" />
        </div>

        <!-- Profile Photo -->
        <div v-if="$page.props.jetstream.managesProfilePhotos">
            <!-- Profile Photo File Input -->
            <input ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">

            <InputLabel for="photo" :value="$t('Photo')" />

            <div class="flex mt-2">
                <!-- Current Profile Photo -->
                <div v-show="!photoPreview">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-8 w-8 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview">
                    <span class="block rounded-full w-8 h-8 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'" />
                </div>

                <button class="ml-2 px-2 text-sm text-gray-400 hover:text-gray-500 transition-colors" type="button"
                    @click.prevent="selectNewPhoto">
                    {{ $t('Change?') }}
                </button>

                <button class="px-2 text-sm text-red-500 hover:text-red-600 rounded-md transition-colors"
                    v-if="user.profile_photo_path" type="button" @click.prevent="deletePhoto">
                    Remove Photo
                </button>
            </div>

            <InputError :message="form.errors.photo" class="mt-2" />
        </div>

        <!-- Birthday -->
        <div class="col-span-2">
            <InputLabel for="birthday" :value="$t('Birthday')" />
            <TextInput id="birthday" v-model="form.birthday" type="text" class="mt-1 block w-full"
                autocomplete="birthday" />
            <InputError :message="form.errors.birthday" class="mt-2" />
        </div>

        <div>
            <InputLabel for="locale" :value="$t('Locale')" />
            <div class="mt-1">

                <select v-model="form.locale" id="locale" name="locale"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="en">{{ $t('English') }}</option>
                    <option value="es">{{ $t('Espanhol') }}</option>
                    <option value="pt">{{ $t('Português') }}</option>
                </select>

            </div>

            <InputError class="mt-2" :message="form.errors.locale" />
        </div>

        <div>
            <InputLabel for="coin" :value="$t('Coin')" />
            <div class="mt-1">

                <select v-model="form.coin" id="coin" name="coin"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="BRL">BRL</option>
                </select>

            </div>

            <InputError class="mt-2" :message="form.errors.locale" />
        </div>

        <div class="col-span-2">
            <InputLabel for="full" :value="$t('Full')" />
            <Checkbox id="full" v-model:checked="form.full" name="full" />
            <InputError class="mt-2" :message="form.errors.full" />
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
</template>
