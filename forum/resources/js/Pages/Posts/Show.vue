
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import Comment from '@/Components/Comment.vue';

defineProps({
    post: Object,
    comments: Object,
});
</script>

<template>
    <AppLayout title="Post">
        <Container>
            <h1 class="text-2xl font-bold">
                {{ post.data.title }}
            </h1>

            <p class="mt-2 text-sm text-gray-600">
                By {{ post.data.user?.name ?? 'Unknown' }}
            </p>

            <div class="mt-8 whitespace-pre-line">
                {{ post.data.body }}
            </div>

            <div class="mt-12">
                <h2 class="text-xl font-semibold">
                    Comments
                </h2>

                <ul class="divide-y mt-4">
                    <li
                        v-for="comment in comments.data"
                        :key="comment.id"
                        class="px-2 py-4"
                    >
                        <Comment :comment="comment" />
                    </li>
                </ul>

                <Pagination
                    :meta="comments.meta"
                    class="mt-2"
                />
            </div>
        </Container>
    </AppLayout>
</template>
