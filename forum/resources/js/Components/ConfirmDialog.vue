
<script setup>
import { ref } from 'vue';

const visible = ref(false);
const message = ref('');
let resolveConfirmation = null;

function confirm(text) {
    message.value = text;
    visible.value = true;

    return new Promise((resolve) => {
        resolveConfirmation = resolve;
    });
}

function finish(result) {
    visible.value = false;

    if (resolveConfirmation) {
        resolveConfirmation(result);
        resolveConfirmation = null;
    }
}

defineExpose({ confirm });
</script>

<template>
    <div
        v-if="visible"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
            <h2 class="text-lg font-semibold text-gray-900">
                Please Confirm
            </h2>

            <p class="mt-3 text-sm text-gray-600">
                {{ message }}
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    class="rounded border px-4 py-2 text-sm"
                    @click="finish(false)"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="rounded bg-gray-800 px-4 py-2 text-sm text-white"
                    @click="finish(true)"
                >
                    Confirm
                </button>
            </div>
        </div>
    </div>
</template>
