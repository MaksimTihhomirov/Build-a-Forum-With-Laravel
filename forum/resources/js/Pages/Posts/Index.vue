
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    posts: Object,
});

function formattedDate(post) {
    return new Intl.RelativeTimeFormat('en', {
        numeric: 'auto',
    }).format(
        -Math.max(
            1,
            Math.floor(
                (Date.now() - new Date(post.created_at).getTime())
                / (1000 * 60 * 60 * 24)
            )
        ),
        'day'
    );
}
</script>

<template>
    <AppLayout title="Posts">
        <Container>
            <ul class="divide-y">
                <li
                    v-for="post in posts.data"
                    :key="post.id"
                    class="px-2 py-4"
                >
                    <Link :href="route('posts.show', post.id)">
                        <span class="font-bold text-lg">
                            {{ post.title }}
                        </span>

                        <span class="block mt-1 text-sm text-gray-600">
                            {{ formattedDate(post) }}
                            by {{ post.user?.name ?? 'Unknown' }}
                        </span>
                    </Link>
                </li>
            </ul>

            <Pagination
                :meta="posts.meta"
                class="mt-2"
            />
        </Container>
    </AppLayout>
</template>
