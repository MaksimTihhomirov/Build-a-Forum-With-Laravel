
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    posts: Object,
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
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                    <div v-if="posts.data.length === 0">
                        No posts yet.
                    </div>

                    <div v-else>
                        <div
                            v-for="post in posts.data"
                            :key="post.id"
                            class="py-4 border-b"
                        >
                            <h3 class="font-semibold text-lg">
                                {{ post.title }}
                            </h3>

                            <p class="text-gray-600 mt-2">
                                {{ post.body }}
                            </p>

                            <p
                                v-if="post.user"
                                class="text-sm text-gray-400 mt-2"
                            >
                                By {{ post.user.name }}
                            </p>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-2">
                            <template
                                v-for="(link, index) in posts.links"
                                :key="index"
                            >
                                <span
                                    v-if="!link.url"
                                    v-html="link.label"
                                    class="px-3 py-2 border rounded text-gray-400"
                                />

                                <Link
                                    v-else
                                    :href="link.url"
                                    v-html="link.label"
                                    class="px-3 py-2 border rounded"
                                    :class="{
                                        'bg-indigo-600 text-white': link.active,
                                        'bg-white text-gray-700': !link.active
                                    }"
                                />
                            </template>
                        </div>

                        <p class="text-sm text-gray-500 mt-4">
                            Showing {{ posts.from }} to {{ posts.to }}
                            of {{ posts.total }} results
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
