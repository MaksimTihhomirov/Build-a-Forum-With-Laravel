
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    body: '',
});

const submit = () => {
    form.post(route('posts.store'));
};
</script>

<template>
    <AppLayout title="Create Post">
        <Container>
            <div class="py-8">
                <h1 class="mb-6 text-2xl font-bold">
                    Create Post
                </h1>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="title" class="block mb-2 font-medium">
                            Title
                        </label>

                        <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            placeholder="Give it a great title..."
                            class="w-full rounded border-gray-300"
                        />

                        <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <div>
                        <label for="body" class="block mb-2 font-medium">
                            Body
                        </label>

                        <textarea
                            id="body"
                            v-model="form.body"
                            rows="8"
                            placeholder="Give it a great body..."
                            class="w-full rounded border-gray-300"
                        ></textarea>

                        <p v-if="form.errors.body" class="mt-1 text-sm text-red-600">
                            {{ form.errors.body }}
                        </p>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded bg-blue-600 px-4 py-2 text-white disabled:opacity-50"
                    >
                        {{ form.processing ? 'Creating...' : 'Create Post' }}
                    </button>
                </form>
            </div>
        </Container>
    </AppLayout>
</template>
