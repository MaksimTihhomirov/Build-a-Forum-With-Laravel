
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import Pill from '@/Components/Pill.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    posts: Object,
    topics: Object,
    selectedTopic: Object,
});

const allTopics = computed(() => {
    return Array.isArray(props.topics)
        ? props.topics
        : props.topics?.data ?? [];
});

const currentTopic = computed(() => {
    return props.selectedTopic?.data
        ?? props.selectedTopic
        ?? null;
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
            <div class="py-6">
                <h1 class="text-2xl font-bold">
                    {{ currentTopic?.name ?? 'All Posts' }}
                </h1>

                <p
                    v-if="currentTopic?.description"
                    class="mt-1 text-sm text-gray-600"
                >
                    {{ currentTopic.description }}
                </p>

                <div class="mt-4 flex flex-wrap gap-2">
                    <Pill
                        :href="route('posts.index')"
                        :active="!currentTopic"
                    >
                        All Posts
                    </Pill>

                    <Pill
                        v-for="topic in allTopics"
                        :key="topic.id"
                        :href="route('posts.index', { topic: topic.slug })"
                        :active="currentTopic?.id === topic.id"
                    >
                        {{ topic.name }}
                    </Pill>
                </div>
            </div>

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

            <p
                v-if="posts.data.length === 0"
                class="py-8 text-center text-gray-500"
            >
                No posts found in this topic.
            </p>

            <Pagination
                :meta="posts.meta"
                class="mt-2"
            />
        </Container>
    </AppLayout>
</template>
