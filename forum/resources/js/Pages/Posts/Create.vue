
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import MarkdownEditor from '@/Components/MarkdownEditor.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    topics: {
        type: [Object, Array],
        required: true,
    },
});

const form = useForm({
    title: '',
    topic_id: '',
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

                        <p
                            v-if="form.errors.title"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <div>
                        <label for="topic_id" class="block mb-2 font-medium">
                            Topic
                        </label>

                        <select
                            id="topic_id"
                            v-model="form.topic_id"
                            class="w-full rounded border-gray-300"
                        >
                            <option value="" disabled>
                                Select a topic
                            </option>

                            <option
                                v-for="topic in (Array.isArray(topics) ? topics : topics.data)"
                                :key="topic.id"
                                :value="topic.id"
                            >
                                {{ topic.name }}
                            </option>
                        </select>

                        <p
                            v-if="form.errors.topic_id"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.topic_id }}
                        </p>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium">
                            Body
                        </label>

                        <MarkdownEditor v-model="form.body" />

                        <p
                            v-if="form.errors.body"
                            class="mt-1 text-sm text-red-600"
                        >
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
