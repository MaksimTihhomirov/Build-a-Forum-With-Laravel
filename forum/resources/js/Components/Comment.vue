
<script setup>
defineProps({
    comment: Object,
});
</script>

<template>
    <div>
        <span class="text-sm">
            {{ comment.body }}
        </span>

        <span class="first-letter:uppercase block pt-1 text-sm text-gray-600">
            By {{ comment.user?.name ?? 'Unknown' }}
        </span>
    </div>
</template>
