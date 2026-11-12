
<script setup>
import { computed, onBeforeUnmount } from 'vue';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import { Markdown } from '@tiptap/markdown';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

const editor = useEditor({
    content: props.modelValue,
    contentType: 'markdown',
    extensions: [
        StarterKit,
        Markdown,
    ],
    editorProps: {
        attributes: {
            class: 'w-full max-w-none min-h-40 p-4 text-left focus:outline-none',
        },
    },
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getMarkdown());
    },
});

onBeforeUnmount(() => {
    editor.value?.destroy();
});
</script>

<template>
    <div class="rounded-md border border-gray-300 bg-white">
        <EditorContent :editor="editor" />
    </div>
</template>
