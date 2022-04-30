<script setup>
const props = defineProps({
    post: Object
})
</script>

<template>
    <div class="py-10">
        <h2 class="text-3xl font-bold">{{ post.title }}</h2>
        <img :src="post.image_url" v-if="post.image_url" class="mt-6 w-full rounded-xl object-cover h-64 shadow" />

        <div class="mt-4 flex items-center space-x-3 font-normal text-sm text-gray-500">
            <div class="flex items-center space-x-2">
                <img :src="post.author.avatar" :alt="post.author.name" class="rounded-full w-5 h-5">
                <span>{{ post.author.name }}</span>
            </div>
            <span>&middot;</span>
            <div class="flex items-center space-x-2">
                <span>{{ post.created_at_pretty }}</span>
            </div>
            <span>&middot;</span>
            <div class="flex items-center space-x-2">
                <img :src="post.author.avatar" :alt="post.author.name" class="rounded-full w-5 h-5">
                <span>{{ post.author.name }}</span>
            </div>
        </div>
    </div>
</template>
