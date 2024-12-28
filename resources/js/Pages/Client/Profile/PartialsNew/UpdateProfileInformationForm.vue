<script setup>
import { ref } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
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
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('client.profile-information.update'), {
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

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('client.current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
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
            <TextInput
                id="first_name"
                v-model="form.first_name"
                type="text"
                class="mt-1 block w-full"
                autocomplete="first_name"
            />
            <InputError :message="form.errors.first_name" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div>
            <InputLabel for="last_name" :value="$t('Last name')" />
            <TextInput
                id="last_name"
                v-model="form.last_name"
                type="text"
                class="mt-1 block w-full"
                autocomplete="last_name"
            />
            <InputError :message="form.errors.last_name" class="mt-2" />
        </div>
        

        <!-- Name -->
        <div>
            <InputLabel for="name" :value="$t('Username (optional)')" />
            <TextInput
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full"
                autocomplete="name"
            />
            <InputError :message="form.errors.name" class="mt-2" />
        </div>

        <!-- Profile Photo -->
        <div v-if="$page.props.jetstream.managesProfilePhotos">
            <!-- Profile Photo File Input -->
            <input
                ref="photoInput"
                type="file"
                class="hidden"
                @change="updatePhotoPreview"
            >

            <InputLabel for="photo" :value="$t('Photo')" />

            <div class="flex mt-2">
                <!-- Current Profile Photo -->
                <div v-show="! photoPreview">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-8 w-8 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview">
                    <span
                        class="block rounded-full w-8 h-8 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'"
                    />
                </div>

                <button class="ml-2 px-2 text-sm text-gray-400 hover:text-gray-500 transition-colors" type="button" @click.prevent="selectNewPhoto">
                    {{ $t('Change?') }}
                </button>

                <button class="px-2 text-sm text-red-500 hover:text-red-600 rounded-md transition-colors" v-if="user.profile_photo_path" type="button" @click.prevent="deletePhoto"
                >
                    Remove Photo
                </button>
            </div>

            <InputError :message="form.errors.photo" class="mt-2" />
        </div>

        <!-- Birthday -->
        <div>
            <InputLabel for="birthday" :value="$t('Birthday')" />
            <TextInput
                id="birthday"
                v-model="form.birthday"
                type="text"
                class="mt-1 block w-full"
                autocomplete="birthday"
            />
            <InputError :message="form.errors.birthday" class="mt-2" />
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
