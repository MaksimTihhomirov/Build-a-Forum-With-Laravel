
<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const show = ref(false);
const style = ref('success');
const message = ref('');

let timeout = null;

watch(
    () => [
        page.props.jetstream?.flash?.banner,
        page.props.jetstream?.flash?.bannerStyle,
    ],
    ([newMessage, newStyle]) => {
        if (timeout) {
            clearTimeout(timeout);
        }

        message.value = newMessage || '';
        style.value = newStyle || 'success';
        show.value = Boolean(message.value);

        if (show.value) {
            timeout = setTimeout(() => {
                show.value = false;
            }, 3000);
        }
    },
    { immediate: true }
);
</script>

<template>
    <div
        v-if="show && message"
        class="fixed left-0 right-0 top-0 z-50"
    >
        <div
            class="flex items-center justify-between px-6 py-4 text-white shadow-lg"
            :class="
                style === 'success'
                    ? 'bg-indigo-600'
                    : 'bg-red-600'
            "
        >
            <span class="text-sm font-medium">
                {{ message }}
            </span>

            <button
                type="button"
                class="ml-4 text-xl text-white"
                @click="show = false"
            >
                &times;
            </button>
        </div>
    </div>
</template>
