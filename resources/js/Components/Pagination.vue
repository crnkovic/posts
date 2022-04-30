<script setup>
import { Link } from '@inertiajs/inertia-vue3'

const props = defineProps({
    data: Object,
})
</script>

<template>
    <section class="flex items-center justify-between">
        <div>
            <Link :href="data.links.prev" v-if="data.links.prev" class="hover:underline text-gray-600 hover:text-gray-800 text-sm font-medium">
                Previous page
            </Link>
        </div>

        <div>
            <Link :href="data.links.next" v-if="data.links.next" class="hover:underline text-gray-600 hover:text-gray-800 text-sm font-medium">
                Next page
            </Link>
        </div>
    </section>
</template>
