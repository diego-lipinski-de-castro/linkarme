<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

const form = useForm({
    description: '',
    urgent: false,
    photo: null,
});

const photoPreview = ref(null);
const photoInput = ref(null);

const submit = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('client.bugs.store'), {
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
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

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="grid grid-cols-1 gap-y-6">
        <!-- First Name -->
        <div>
            <InputLabel for="description" :value="$t('What is going on?')" />
            <textarea rows="5" id="description" v-model="form.description" type="text"
                class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"
                autocomplete="description"></textarea>
            <InputError :message="form.errors.description" class="mt-2" />
        </div>

        <!-- Photo -->
        <div>
            <InputLabel for="photo" :value="$t('Photo')" />
            
            <div class="mt-1">
                <button type="button" @click="selectNewPhoto"
                    
                    :class="['flex w-full max-w-lg justify-center border-dashed border-gray-300', {
                        'rounded-md border-2 px-6 pt-5 pb-6': (photoPreview == null),
                    }]">

                    <span v-show="photoPreview" class="hover:opacity-75 transition-opacity block rounded-md object-contain h-48 w-full bg-contain bg-no-repeat bg-center"  :style="'background-image: url(\'' + photoPreview + '\');'" />

                    <div v-show="!photoPreview" class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                            viewBox="0 0 48 48" aria-hidden="true">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="file-upload"
                                class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                                <span>{{ $t('Upload a file') }}</span>
                                <input ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">
                            </label>
                            <!-- <p class="pl-1">or drag and drop</p> -->
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 1MB</p>
                    </div>
                </button>
            </div>

            <InputError :message="form.errors.photo" class="mt-2" />
        </div>

        <!-- Urgent -->
        <div>
            <InputLabel for="urgent">
                <div class="flex items-center">
                    <Checkbox id="urgent" v-model:checked="form.urgent" name="urgent" />

                    <div class="ml-2 text-red-500">
                        {{ $t('Mark this box if this is a very urgent problem') }}
                    </div>
                </div>
                <InputError class="mt-2" :message="form.errors.urgent" />
            </InputLabel>
        </div>

        <div>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ $t('Send') }}
            </PrimaryButton>

            <ActionMessage :on="form.recentlySuccessful" class="self-end ml-3">
                {{ $t('Submitted.') }}
            </ActionMessage>
        </div>
    </form>
</template>
