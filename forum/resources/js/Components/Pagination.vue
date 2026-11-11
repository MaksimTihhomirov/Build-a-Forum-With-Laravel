
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    meta: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <div
        v-if="meta.links && meta.links.length > 3"
        class="mt-6 flex flex-wrap gap-2"
    >
        <template
            v-for="(link, index) in meta.links"
            :key="index"
        >
            <span
                v-if="!link.url"
                class="rounded border px-3 py-2 text-sm text-gray-400"
                v-html="link.label"
            ></span>

            <Link
                v-else
                :href="link.url"
                preserve-scroll
                class="rounded border px-3 py-2 text-sm"
                :class="
                    link.active
                        ? 'bg-blue-600 text-white'
                        : 'bg-white text-gray-700 hover:bg-gray-100'
                "
                v-html="link.label"
            />
        </template>
    </div>
</template>
