
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    post: Object,
});
</script>

<template>
    <AppLayout title="Post">
        <Head :title="post.title" />

        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

                <Link
                    :href="route('posts.index')"
                    class="text-blue-600 hover:underline"
                >
                    ← Back to posts
                </Link>

                <div class="bg-white shadow rounded-lg p-8 mt-6">

                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ post.title }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-3">
                        By {{ post.user?.name ?? 'Unknown' }}
                    </p>

                    <p class="text-sm text-gray-400 mt-1">
                        {{ new Date(post.created_at).toLocaleDateString() }}
                    </p>

                    <div class="mt-8 text-gray-700 whitespace-pre-line leading-7">
                        {{ post.body }}
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
