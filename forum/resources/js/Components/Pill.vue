
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="inline-flex items-center rounded-full border px-3 py-1 text-sm transition"
        :class="active
            ? 'border-indigo-600 bg-indigo-600 text-white'
            : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-100'"
    >
        <slot />
    </Link>
</template>
