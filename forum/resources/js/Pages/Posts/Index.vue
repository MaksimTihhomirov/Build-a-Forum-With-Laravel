
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import Pill from '@/Components/Pill.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    posts: Object,
    topics: Object,
    selectedTopic: Object,
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const search = ref(props.filters?.query ?? '');

watch(
    () => props.filters?.query,
    (value) => {
        search.value = value ?? '';
    }
);

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

const activeQuery = computed(() => {
    return props.filters?.query ?? '';
});

function searchPosts() {
    router.get(
        route('posts.index'),
        {
            ...(currentTopic.value?.slug
                ? { topic: currentTopic.value.slug }
                : {}),
            ...(search.value.trim()
                ? { query: search.value.trim() }
                : {}),
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
}

function clearSearch() {
    search.value = '';

    router.get(
        route('posts.index'),
        {
            ...(currentTopic.value?.slug
                ? { topic: currentTopic.value.slug }
                : {}),
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
}

function topicUrl(topic = null) {
    return route('posts.index', {
        ...(topic ? { topic: topic.slug } : {}),
        ...(activeQuery.value
            ? { query: activeQuery.value }
            : {}),
    });
}

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
                        :href="topicUrl()"
                        :active="!currentTopic"
                    >
                        All Posts
                    </Pill>

                    <Pill
                        v-for="topic in allTopics"
                        :key="topic.id"
                        :href="topicUrl(topic)"
                        :active="currentTopic?.id === topic.id"
                    >
                        {{ topic.name }}
                    </Pill>
                </div>

                <form
                    class="mt-6"
                    @submit.prevent="searchPosts"
                >
                    <InputLabel
                        for="query"
                        value="Search"
                    />

                    <div class="mt-2 flex gap-2">
                        <TextInput
                            id="query"
                            v-model="search"
                            type="search"
                            class="w-full"
                            placeholder="Search posts..."
                        />

                        <SecondaryButton type="submit">
                            Search
                        </SecondaryButton>

                        <button
                            v-if="activeQuery"
                            type="button"
                            @click="clearSearch"
                            class="rounded-md bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600"
                        >
                            Clear
                        </button>
                    </div>
                </form>
            </div>

            <ul class="divide-y">
                <li
                    v-for="post in posts.data"
                    :key="post.id"
                    class="flex items-center justify-between gap-4 px-2 py-4"
                >
                    <Link
                        :href="route('posts.show', post.id)"
                        class="min-w-0 flex-1"
                    >
                        <span class="text-lg font-bold">
                            {{ post.title }}
                        </span>

                        <span class="mt-1 block text-sm text-gray-600">
                            {{ formattedDate(post) }}
                            by {{ post.user?.name ?? 'Unknown' }}
                        </span>
                    </Link>

                    <Pill
                        v-if="post.topic"
                        :href="topicUrl(post.topic)"
                        class="shrink-0"
                    >
                        {{ post.topic.name }}
                    </Pill>
                </li>
            </ul>

            <p
                v-if="posts.data.length === 0"
                class="py-8 text-center text-gray-500"
            >
                No posts found.
            </p>

            <Pagination
                :meta="posts.meta"
                class="mt-2"
            />
        </Container>
    </AppLayout>
</template>
