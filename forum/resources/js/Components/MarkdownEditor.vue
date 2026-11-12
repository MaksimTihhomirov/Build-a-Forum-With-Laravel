
<script setup>
import { onBeforeUnmount, watch } from 'vue';
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

watch(
    () => props.modelValue,
    (value) => {
        if (!editor.value) return;

        if (editor.value.getMarkdown() !== value) {
            editor.value.commands.setContent(value, {
                contentType: 'markdown',
                emitUpdate: false,
            });
        }
    },
);

onBeforeUnmount(() => {
    editor.value?.destroy();
});
</script>

<template>
    <div class="overflow-hidden rounded-md border border-gray-300 bg-white">

        <!-- Toolbar -->
        <div
            v-if="editor"
            class="flex flex-wrap items-center gap-1 border-b border-gray-200 bg-gray-50 p-2"
        >
            <button
                type="button"
                title="Bold"
                @click="editor.chain().focus().toggleBold().run()"
                :class="editor.isActive('bold') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-200'"
                class="rounded px-3 py-1.5 text-sm font-bold"
            >
                B
            </button>

            <button
                type="button"
                title="Italic"
                @click="editor.chain().focus().toggleItalic().run()"
                :class="editor.isActive('italic') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-200'"
                class="rounded px-3 py-1.5 text-sm italic"
            >
                I
            </button>

            <button
                type="button"
                title="Strikethrough"
                @click="editor.chain().focus().toggleStrike().run()"
                :class="editor.isActive('strike') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-200'"
                class="rounded px-3 py-1.5 text-sm line-through"
            >
                S
            </button>

            <div class="mx-1 h-5 w-px bg-gray-300"></div>

            <button
                type="button"
                title="Heading 1"
                @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
                :class="editor.isActive('heading', { level: 1 }) ? 'bg-indigo-600 text-white' : 'hover:bg-gray-200'"
                class="rounded px-3 py-1.5 text-sm font-semibold"
            >
                H1
            </button>

            <button
                type="button"
                title="Heading 2"
                @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                :class="editor.isActive('heading', { level: 2 }) ? 'bg-indigo-600 text-white' : 'hover:bg-gray-200'"
                class="rounded px-3 py-1.5 text-sm font-semibold"
            >
                H2
            </button>

            <div class="mx-1 h-5 w-px bg-gray-300"></div>

            <button
                type="button"
                title="Bullet List"
                @click="editor.chain().focus().toggleBulletList().run()"
                :class="editor.isActive('bulletList') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-200'"
                class="rounded px-3 py-1.5 text-sm"
            >
                • List
            </button>

            <button
                type="button"
                title="Numbered List"
                @click="editor.chain().focus().toggleOrderedList().run()"
                :class="editor.isActive('orderedList') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-200'"
                class="rounded px-3 py-1.5 text-sm"
            >
                1. List
            </button>

            <button
                type="button"
                title="Quote"
                @click="editor.chain().focus().toggleBlockquote().run()"
                :class="editor.isActive('blockquote') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-200'"
                class="rounded px-3 py-1.5 text-sm"
            >
                “ Quote
            </button>

            <div class="mx-1 h-5 w-px bg-gray-300"></div>

            <button
                type="button"
                title="Undo"
                :disabled="!editor.can().undo()"
                @click="editor.chain().focus().undo().run()"
                class="rounded px-3 py-1.5 text-sm hover:bg-gray-200 disabled:opacity-30"
            >
                ↶
            </button>

            <button
                type="button"
                title="Redo"
                :disabled="!editor.can().redo()"
                @click="editor.chain().focus().redo().run()"
                class="rounded px-3 py-1.5 text-sm hover:bg-gray-200 disabled:opacity-30"
            >
                ↷
            </button>
        </div>

        <!-- Editor -->
        <EditorContent :editor="editor" />
    </div>
</template>
