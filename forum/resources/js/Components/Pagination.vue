
<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    meta: {
        type: Object,
        default: () => ({}),
    },
    only: {
        type: Array,
        default: () => [],
    },
});

const only = computed(() =>
    props.only.length === 0 ? [] : [...props.only, 'jetstream']
);

const links = computed(() => props.meta?.links ?? []);
</script>

<template>
    <div
        v-if="links.length > 3"
        class="mt-6 flex flex-wrap gap-2"
    >
        <template v-for="(link, index) in links" :key="index">
            <span
                v-if="!link.url"
                class="rounded border px-3 py-2 text-sm text-gray-400"
                v-html="link.label"
            ></span>

            <Link
                v-else
                :href="link.url"
                :only="only"
                preserve-scroll
                preserve-state
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
