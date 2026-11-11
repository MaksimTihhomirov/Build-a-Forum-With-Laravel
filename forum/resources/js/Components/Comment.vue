
<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    comment: Object,
});

const page = usePage();

const canDelete = computed(() => {
    return page.props.auth?.user?.id === props.comment.user?.id;
});

const deleteComment = () => {
    if (confirm('Are you sure you want to delete this comment?')) {
        const url = new URL(window.location.href);
        const currentPage = url.searchParams.get('page');

        router.delete(
            route('comments.destroy', props.comment.id) +
                (currentPage ? `?page=${currentPage}` : ''),
            {
                preserveScroll: true,
            }
        );
    }
};
</script>

<template>
    <div>
        <span class="text-sm">
            {{ comment.body }}
        </span>

        <span class="first-letter:uppercase block pt-1 text-sm text-gray-600">
            By {{ comment.user?.name ?? 'Unknown' }}
        </span>

        <button
            v-if="canDelete"
            @click="deleteComment"
            type="button"
            class="mt-2 text-sm text-red-600 hover:text-red-800"
        >
            Delete
        </button>
    </div>
</template>
