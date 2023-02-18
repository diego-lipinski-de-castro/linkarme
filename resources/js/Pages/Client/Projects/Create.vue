
<script setup>
import { ref } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { LinkIcon, PlusIcon, QuestionMarkCircleIcon } from '@heroicons/vue/20/solid'
import { ColorPicker } from 'vue-accessible-color-picker'
import { useForm } from '@inertiajs/inertia-vue3'

const emit = defineEmits(['close']);

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const close = () => {
    emit('close');
};

const form = useForm({
    name: '',
    color: '', 
});

const submit = () => {
    form.post(route('client.projects.store'), {
        onSuccess: () => close()
    });
};

const updateColor = (event) => {
    form.color = event.cssColor
}
</script>

<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-10" @close="close">
            <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black bg-opacity-25 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                        <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700"
                            enter-from="translate-x-full" enter-to="translate-x-0"
                            leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0"
                            leave-to="translate-x-full">
                            <DialogPanel class="pointer-events-auto w-screen max-w-md">
                                <form @submit.prevent="submit" class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl">
                                    <div class="h-0 flex-1 overflow-y-auto">
                                        <div class="bg-gray-50 py-6 px-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <DialogTitle class="text-lg font-medium text-gray-900">{{ $t('New Project') }}
                                                </DialogTitle>
                                                <div class="ml-3 flex h-7 items-center">
                                                    <button @click="close" type="button"
                                                        class="rounded-md text-gray-400 hover:text-gray-500">
                                                        <span class="sr-only">Close panel</span>
                                                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-1">
                                                <p class="text-sm text-gray-500">{{ $t('Get started by filling in the information below to create your new project.') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-1 flex-col justify-between">
                                            <div class="divide-y divide-gray-200 px-4 sm:px-6">
                                                <div class="space-y-6 pt-6 pb-5">
                                                    <div>
                                                        <label for="project-name"
                                                            class="block text-sm font-medium text-gray-900">{{ $t('Project name') }}</label>
                                                        <div class="mt-1">
                                                            <input v-model="form.name" type="text" name="project-name" id="project-name"
                                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="description" class="block text-sm font-medium text-gray-900">{{ $t('Color') }}</label>
                                                        <ColorPicker
                                                            @color-change="updateColor"
                                                            :visible-formats="['hex']"
                                                            defaultFormat="hex"
                                                            alpha-channel="hide"
                                                            class="mt-1"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-shrink-0 justify-end px-4 py-4">
                                        <button type="button"
                                            class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                            @click="close">{{ $t('Cancel') }}</button>
                                        <button type="submit"
                                            class="ml-4 inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">{{ $t('Save') }}</button>
                                    </div>
                                </form>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
