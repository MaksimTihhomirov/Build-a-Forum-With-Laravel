
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import Comment from '@/Components/Comment.vue';
import InputError from '@/Components/InputError.vue';
import { router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    post: Object,
    comments: Object,
});

const form = useForm({
    body: '',
});

function submit() {
    form.post(route('posts.comments.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => form.reset('body'),
    });
}

function likePost() {
    router.post(
        route('likes.store', ['post', props.post.id]),
        {},
        {
            preserveScroll: true,
        }
    );
}

function unlikePost() {
    router.delete(
        route('likes.destroy', ['post', props.post.id]),
        {
            preserveScroll: true,
        }
    );
}
</script>

<template>
    <AppLayout title="Post">
        <Container>
            <div class="py-8">
                <h1 class="text-2xl font-bold">
                    {{ post.title }}
                </h1>

                <p class="mt-2 text-sm text-gray-600">
                    By {{ post.user?.name ?? 'Unknown' }}
                </p>

                <div class="mt-4 flex items-center gap-3">
                    <span class="text-sm text-gray-600">
                        {{ post.likes_count ?? 0 }}
                        {{ post.likes_count === 1 ? 'like' : 'likes' }}
                    </span>

                    <button
                        v-if="post.can_like"
                        type="button"
                        @click="likePost"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                    >
                        Like Post
                    </button>

                    <button
                        v-if="post.can_unlike"
                        type="button"
                        @click="unlikePost"
                        class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                    >
                        Unlike Post
                    </button>
                </div>

                <div
                    class="prose mt-8 max-w-none"
                    v-html="post.html"
                ></div>

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

                    <ul class="mt-6 divide-y">
                        <li
                            v-for="comment in comments.data"
                            :key="comment.id"
                            class="px-2 py-4"
                        >
                            <Comment
                                :comment="comment"
                                :current-page="comments.meta.current_page"
                            />
                        </li>
                    </ul>

                    <Pagination
                        :meta="comments.meta"
                        :only="['comments']"
                        class="mt-2"
                    />
                </div>
            </div>
        </Container>
    </AppLayout>
</template>
