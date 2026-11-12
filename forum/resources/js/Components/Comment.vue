<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';

const props = defineProps({
    comment: Object,
});

const page = usePage();
const confirmDialog = ref(null);

const canEdit = computed(() => {
    return page.props.auth?.user?.id === props.comment.user?.id;
});

const isEditing = ref(false);
const editedBody = ref(props.comment.body);
const error = ref('');
const isSaving = ref(false);
const isDeleting = ref(false);

const currentPageQuery = () => {
    const url = new URL(window.location.href);
    const currentPage = url.searchParams.get('page');

    return currentPage ? `?page=${currentPage}` : '';
};

const startEditing = () => {
    editedBody.value = props.comment.body;
    error.value = '';
    isEditing.value = true;
};

const cancelEditing = () => {
    editedBody.value = props.comment.body;
    error.value = '';
    isEditing.value = false;
};

const updateComment = async () => {
    if (!editedBody.value.trim()) {
        error.value = 'Comment cannot be empty.';
        return;
    }

    const confirmed = await confirmDialog.value.confirm(
        'Are you sure you want to update this comment?'
    );

    if (!confirmed) {
        return;
    }

    isSaving.value = true;
    error.value = '';

    router.put(
        route('comments.update', props.comment.id) + currentPageQuery(),
        {
            body: editedBody.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                isEditing.value = false;
            },
            onError: (errors) => {
                error.value = errors.body || 'Could not update comment.';
            },
            onFinish: () => {
                isSaving.value = false;
            },
        }
    );
};

const deleteComment = async () => {
    const confirmed = await confirmDialog.value.confirm(
        'Are you sure you want to delete this comment?'
    );

    if (!confirmed) {
        return;
    }

    isDeleting.value = true;

    router.delete(
        route('comments.destroy', props.comment.id) + currentPageQuery(),
        {
            preserveScroll: true,
            onFinish: () => {
                isDeleting.value = false;
            },
        }
    );
};
</script>

<template>
    <div>
        <div v-if="isEditing">
            <textarea
                v-model="editedBody"
                rows="3"
                class="w-full rounded border border-gray-300 p-2 text-sm"
            ></textarea>

            <p
                v-if="error"
                class="mt-1 text-sm text-red-600"
            >
                {{ error }}
            </p>

            <div class="mt-2 flex gap-3">
                <button
                    type="button"
                    @click="updateComment"
                    :disabled="isSaving"
                    class="text-sm text-blue-600 hover:text-blue-800 disabled:opacity-50"
                >
                    {{ isSaving ? 'Saving...' : 'Save' }}
                </button>

                <button
                    type="button"
                    @click="cancelEditing"
                    :disabled="isSaving"
                    class="text-sm text-gray-600 hover:text-gray-800"
                >
                    Cancel
                </button>
            </div>
        </div>

        <div
            v-else
            class="prose prose-sm max-w-none"
            v-html="comment.html"
        ></div>

        <span class="first-letter:uppercase block pt-1 text-sm text-gray-600">
            By {{ comment.user?.name ?? 'Unknown' }}
        </span>

        <div
            v-if="canEdit && !isEditing"
            class="mt-2 flex gap-4"
        >
            <button
                @click="startEditing"
                type="button"
                class="text-sm text-blue-600 hover:text-blue-800"
            >
                Edit
            </button>

            <button
                @click="deleteComment"
                type="button"
                :disabled="isDeleting"
                class="text-sm text-red-600 hover:text-red-800 disabled:opacity-50"
            >
                {{ isDeleting ? 'Deleting...' : 'Delete' }}
            </button>
        </div>

        <ConfirmDialog ref="confirmDialog" />
    </div>
</template>