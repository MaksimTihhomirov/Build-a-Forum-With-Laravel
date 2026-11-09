
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    posts: Array,
});
</script>

<template>
    <AppLayout title="Posts">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">
                Posts
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow rounded-lg">
                    <div
                        v-for="post in posts"
                        :key="post.id"
                        class="py-3 border-b"
                    >
                        {{ post.title }}
                    </div>

                    <p v-if="posts.length === 0">
                        No posts yet.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
