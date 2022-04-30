<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import BreezeButton from '@/Components/Button.vue'

const form = useForm({
    title: '',
    image: null,
})
</script>

<template>
    <Head title="Publish post" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Publish new post
            </h2>
        </template>

        <div class="py-12">
            <form @submit.prevent="form.post('/posts')" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 space-y-6">
                        <BreezeValidationErrors class="mb-4" />

                        <div>
                            <BreezeLabel for="title" value="Post Title" />
                            <BreezeInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" />
                        </div>

                        <div>
                            <BreezeLabel for="image" value="Cover Image" />
                            <input type="file" accept="image/*" @change="form.image = $event.target.files[0]" id="image" />
                        </div>

                        <div>
                            <BreezeButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Publish</BreezeButton>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </BreezeAuthenticatedLayout>
</template>
