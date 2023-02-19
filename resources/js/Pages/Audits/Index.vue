<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AppSuspense from '../../Layouts/AppSuspense.vue';

const props = defineProps({
    title: String,
    audits: Array,
});

</script>
    
<template>
    <AppSuspense>
        <AppLayout title="Audits">
            <ul v-if="audits.length > 0" role="list" class="mt-6 divide-y divide-gray-200 bg-white rounded-md shadow">
                <li v-for="(audit, index) in audits" :key="index">

                    <div>
                        <div class="p-5">
                            <div class="grid grid-cols-12">
                                <div class="col-span-3">
                                    <strong>audit_id</strong>
                                </div>
                                <div class="col-span-9">{{ audit.metadata.audit_id }}</div>
                            </div>
                            <div class="grid grid-cols-12">
                                <div class="col-span-3">
                                    <strong>audit_event</strong>
                                </div>
                                <div class="col-span-9">{{ audit.metadata.audit_event }}</div>
                            </div>
                            <div class="grid grid-cols-12">
                                <div class="col-span-3">
                                    <strong>user_name</strong>
                                </div>
                                <div class="col-span-9">{{ audit.metadata.user_name }}</div>
                            </div>
                            <div class="grid grid-cols-12">
                                <div class="col-span-3">
                                    <strong>audit_ip_address</strong>
                                </div>
                                <div class="col-span-9">{{ audit.metadata.audit_ip_address }}</div>
                            </div>
                            <div class="grid grid-cols-12">
                                <div class="col-span-3">
                                    <strong>audit_user_agent</strong>
                                </div>
                                <div class="col-span-9">{{ audit.metadata.audit_user_agent }}</div>
                            </div>
                            <div class="grid grid-cols-12">
                                <div class="col-span-3">
                                    <strong>audit_tags</strong>
                                </div>
                                <div class="col-span-9">{{ audit.metadata.audit_tags?.join() }}</div>
                            </div>
                            <div class="grid grid-cols-12">
                                <div class="col-span-3">
                                    <strong>audit_url</strong>
                                </div>
                                <div class="col-span-9">{{ audit.metadata.audit_url }}</div>
                            </div>
                        </div>

                        <hr />

                        <div class="p-5">
                            <table class="divide-y divide-gray-300 w-full border">
                                <thead>
                                    <tr class="divide-x divide-gray-200">
                                        <th>attribute</th>
                                        <th>old</th>
                                        <th>new</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="(value, attribute) in audit.modified" :key="attribute"
                                        class="divide-x divide-gray-200 even:bg-slate-50">
                                        <td class="p-2 "><strong>{{ attribute }}</strong></td>
                                        <td class="p-2 bg-red-100">{{ value.old }}</td>
                                        <td class="p-2 bg-green-100">{{ value.new }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </li>
            </ul>
        </AppLayout>
    </AppSuspense>
</template>
    