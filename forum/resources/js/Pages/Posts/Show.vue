
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import Comment from '@/Components/Comment.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    post: Object,
    comments: Object,
});

const form = useForm({
    body: '',
});

function submit() {
    form.post(route('comments.store', props.post.data.id), {
        preserveScroll: true,
        onSuccess: () => form.reset('body'),
    });
}
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

                <form
                    class="mt-4"
                    @submit.prevent="submit"
                >
                    <label
                        for="body"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Comment
                    </label>

                    <textarea
                        id="body"
                        v-model="form.body"
                        rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Write your comment..."
                    ></textarea>

                    <InputError
                        :message="form.errors.body"
                        class="mt-2"
                    />

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="mt-3 rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700 disabled:opacity-50"
                    >
                        Add Comment
                    </button>
                </form>

                <ul class="divide-y mt-6">
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
