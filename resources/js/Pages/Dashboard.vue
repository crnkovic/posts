<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import Pagination from '@/components/Pagination';

const props = defineProps({
    posts: Object
})
</script>

<template>
    <Head title="My Posts" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Posts
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg divide-y">
                    <div class="p-6 bg-white flex justify-between items-center" v-for="post in posts.data">
                        <div>
                            <div>{{ post.title }}</div>
                            <div class="mt-2 text-xs text-gray-500">{{ post.created_at_pretty }}</div>
                        </div>

                        <div>
                            <Link :href="`/posts/${post.id}`" as="button" method="delete">Delete</Link>
                        </div>
                    </div>
                </div>

                <Pagination class="mt-4" :data="posts" />
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
