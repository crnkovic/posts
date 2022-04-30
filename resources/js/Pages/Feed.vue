<script setup>
import PostCard from '@/components/PostCard'
import Pagination from '@/components/Pagination'
import { Head, Link } from '@inertiajs/inertia-vue3'

defineProps({
    posts: Object
})
</script>

<template>
    <Head title="Feed" />

    <div class="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0">
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="hover:underline text-gray-600 hover:text-gray-800 text-sm font-medium">
                My Posts
            </Link>

            <template v-else>
                <Link :href="route('login')" class="hover:underline text-gray-600 hover:text-gray-800 text-sm font-medium">
                    Log in
                </Link>

                <Link :href="route('register')" class="ml-4 hover:underline text-gray-600 hover:text-gray-800 text-sm font-medium">
                    Register
                </Link>
            </template>
        </div>

        <div class="max-w-6xl w-full mx-auto sm:px-6 lg:px-8 divide-y divide-gray-200">
            <PostCard
                v-for="post in posts.data"
                :post="post"
                :key="post.id"
            />

            <Pagination class="pt-4" :data="posts" />
        </div>
    </div>
</template>
