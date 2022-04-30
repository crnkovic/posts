<script setup>
import { ref } from 'vue';

const props = defineProps({
    post: Object
})

let loadingAllLikes = ref(true)
let showingAllLikes = ref(false)
let likes = ref([])
const showAllLikes = () => {
    loadingAllLikes.value = true

    axios.get('/api/posts/'+props.post.id+'/likes').then(({ data }) => {
        likes.value = data.likes

        showingAllLikes.value = true
        loadingAllLikes.value = false
    })
}

let likedByCurrentUser = ref(props.post.liked_by_current_user || false)

const like = () => {
    axios.post('/api/posts/'+props.post.id+'/likes')

    likedByCurrentUser.value = true
}

const unlike = () => {
    axios.delete('/api/posts/'+props.post.id+'/likes')

    likedByCurrentUser.value = false
}
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
            <div class="flex items-center">
                <div class="inline-flex items-center space-x-2" v-for="user in post.likes">
                    <img :src="user.avatar" :alt="user.name" class="rounded-full w-5 h-5">
                </div>

                <span class="ml-2" v-if="post.total_likes-post.likes.length > 0">and {{ post.total_likes-post.likes.length }} others</span>
                <span class="ml-1">have liked the post.</span>
                <button type="button" class="inline-flex ml-2 text-blue-500 hover:underline" @click.prevent="showAllLikes">View all</button>

                <template v-if="$page.props.auth.user">
                    <button type="button" class="inline-flex ml-2 text-blue-500 hover:underline" @click.prevent="like" v-if="! likedByCurrentUser">Like</button>
                    <button type="button" class="inline-flex ml-2 text-blue-500 hover:underline" @click.prevent="unlike" v-else>Unlike</button>
                </template>
            </div>
        </div>

        <div v-if="showingAllLikes">
            <span v-if="loadingAllLikes">
                Loading...
            </span>

            <span v-else>
                <span>All likes (this should be displayed pretty, but yeah....): </span>
                <span v-for="user in likes">{{ user.name }},</span>
            </span>
        </div>
    </div>
</template>
