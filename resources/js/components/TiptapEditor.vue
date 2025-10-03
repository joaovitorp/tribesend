<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3';
import { BubbleMenu as TiptapBubbleMenu } from '@tiptap/vue-3/menus';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import TextAlign from '@tiptap/extension-text-align';
import Underline from '@tiptap/extension-underline';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { 
    Bold, 
    Italic, 
    Strikethrough, 
    UnderlineIcon,
    List, 
    ListOrdered, 
    AlignLeft, 
    AlignCenter, 
    AlignRight,
    Link2,
    Unlink
} from 'lucide-vue-next';
import { watch, onBeforeUnmount } from 'vue';

interface Props {
    modelValue: string;
    editable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    editable: true,
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const editor = useEditor({
    extensions: [
        StarterKit,
        Link.configure({
            openOnClick: false,
        }),
        TextAlign.configure({
            types: ['heading', 'paragraph'],
        }),
        Underline,
    ],
    content: props.modelValue,
    editable: props.editable,
    onUpdate: () => {
        if (editor.value) {
            emit('update:modelValue', editor.value.getHTML());
        }
    },
});

watch(() => props.modelValue, (value) => {
    if (editor.value) {
        const isSame = editor.value.getHTML() === value;
        
        if (!isSame) {
            editor.value.commands.setContent(value, false);
        }
    }
});

watch(() => props.editable, (value) => {
    if (editor.value) {
        editor.value.setEditable(value);
    }
});

onBeforeUnmount(() => {
    if (editor.value) {
        editor.value.destroy();
    }
});

const setLink = () => {
    if (!editor.value) {
        return;
    }

    const previousUrl = editor.value.getAttributes('link').href;
    const url = window.prompt('URL', previousUrl);

    if (url === null) {
        return;
    }

    if (url === '') {
        editor.value.chain().focus().extendMarkRange('link').unsetLink().run();
        return;
    }

    editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
};
</script>

<template>
    <div class="tiptap-editor w-full">
        <!-- Bubble Menu - aparece ao selecionar texto -->
        <TiptapBubbleMenu 
            v-if="editor" 
            :editor="editor" 
            :tippy-options="{ duration: 100, placement: 'top' }"
            class="flex items-center gap-0.5 rounded-lg border border-border bg-background p-1 shadow-lg"
        >
            <!-- Text formatting -->
            <Button
                type="button"
                variant="ghost"
                size="sm"
                class="h-8 w-8 p-0"
                :class="{ 'bg-accent': editor.isActive('bold') }"
                @click="editor.chain().focus().toggleBold().run()"
            >
                <Bold class="h-4 w-4" />
            </Button>
            
            <Button
                type="button"
                variant="ghost"
                size="sm"
                class="h-8 w-8 p-0"
                :class="{ 'bg-accent': editor.isActive('italic') }"
                @click="editor.chain().focus().toggleItalic().run()"
            >
                <Italic class="h-4 w-4" />
            </Button>
            
            <Button
                type="button"
                variant="ghost"
                size="sm"
                class="h-8 w-8 p-0"
                :class="{ 'bg-accent': editor.isActive('underline') }"
                @click="editor.chain().focus().toggleUnderline().run()"
            >
                <UnderlineIcon class="h-4 w-4" />
            </Button>
            
            <Button
                type="button"
                variant="ghost"
                size="sm"
                class="h-8 w-8 p-0"
                :class="{ 'bg-accent': editor.isActive('strike') }"
                @click="editor.chain().focus().toggleStrike().run()"
            >
                <Strikethrough class="h-4 w-4" />
            </Button>

            <Separator orientation="vertical" class="mx-1 h-6" />

            <!-- Lists -->
            <Button
                type="button"
                variant="ghost"
                size="sm"
                class="h-8 w-8 p-0"
                :class="{ 'bg-accent': editor.isActive('bulletList') }"
                @click="editor.chain().focus().toggleBulletList().run()"
            >
                <List class="h-4 w-4" />
            </Button>
            
            <Button
                type="button"
                variant="ghost"
                size="sm"
                class="h-8 w-8 p-0"
                :class="{ 'bg-accent': editor.isActive('orderedList') }"
                @click="editor.chain().focus().toggleOrderedList().run()"
            >
                <ListOrdered class="h-4 w-4" />
            </Button>

            <Separator orientation="vertical" class="mx-1 h-6" />

            <!-- Text align -->
            <Button
                type="button"
                variant="ghost"
                size="sm"
                class="h-8 w-8 p-0"
                :class="{ 'bg-accent': editor.isActive({ textAlign: 'left' }) }"
                @click="editor.chain().focus().setTextAlign('left').run()"
            >
                <AlignLeft class="h-4 w-4" />
            </Button>
            
            <Button
                type="button"
                variant="ghost"
                size="sm"
                class="h-8 w-8 p-0"
                :class="{ 'bg-accent': editor.isActive({ textAlign: 'center' }) }"
                @click="editor.chain().focus().setTextAlign('center').run()"
            >
                <AlignCenter class="h-4 w-4" />
            </Button>
            
            <Button
                type="button"
                variant="ghost"
                size="sm"
                class="h-8 w-8 p-0"
                :class="{ 'bg-accent': editor.isActive({ textAlign: 'right' }) }"
                @click="editor.chain().focus().setTextAlign('right').run()"
            >
                <AlignRight class="h-4 w-4" />
            </Button>

            <Separator orientation="vertical" class="mx-1 h-6" />

            <!-- Links -->
            <Button
                type="button"
                variant="ghost"
                size="sm"
                class="h-8 w-8 p-0"
                :class="{ 'bg-accent': editor.isActive('link') }"
                @click="setLink"
            >
                <Link2 class="h-4 w-4" />
            </Button>
            
            <Button
                type="button"
                variant="ghost"
                size="sm"
                class="h-8 w-8 p-0"
                :disabled="!editor.isActive('link')"
                @click="editor.chain().focus().unsetLink().run()"
            >
                <Unlink class="h-4 w-4" />
            </Button>
        </TiptapBubbleMenu>
        
        <EditorContent 
            :editor="editor" 
            class="tiptap-content min-h-[400px] p-0"
        />
    </div>
</template>

<style>
.tiptap-content .tiptap {
    min-height: 400px;
    outline: none;
    font-size: 1rem;
    line-height: 1.75;
    color: hsl(var(--foreground));
}

.tiptap-content .tiptap p {
    margin-bottom: 1.25rem;
}

.tiptap-content .tiptap p:last-child {
    margin-bottom: 0;
}

.tiptap-content .tiptap p:first-child {
    margin-top: 0;
}

.tiptap-content .tiptap h1,
.tiptap-content .tiptap h2,
.tiptap-content .tiptap h3 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.tiptap-content .tiptap h1 {
    font-size: 1.875rem;
    line-height: 2.25rem;
}

.tiptap-content .tiptap h2 {
    font-size: 1.5rem;
    line-height: 2rem;
}

.tiptap-content .tiptap h3 {
    font-size: 1.25rem;
    line-height: 1.75rem;
}

.tiptap-content .tiptap ul,
.tiptap-content .tiptap ol {
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}

.tiptap-content .tiptap ul {
    list-style-type: disc;
}

.tiptap-content .tiptap ol {
    list-style-type: decimal;
}

.tiptap-content .tiptap a {
    color: hsl(var(--primary));
    text-decoration: underline;
}

.tiptap-content .tiptap strong {
    font-weight: 600;
}

.tiptap-content .tiptap em {
    font-style: italic;
}

.tiptap-content .tiptap s {
    text-decoration: line-through;
}

.tiptap-content .tiptap u {
    text-decoration: underline;
}

.tiptap-content .tiptap code {
    background-color: hsl(var(--muted));
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-family: monospace;
}

.tiptap-content .tiptap pre {
    background-color: hsl(var(--muted));
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin-bottom: 1rem;
}

.tiptap-content .tiptap pre code {
    background-color: transparent;
    padding: 0;
}

/* Placeholder text */
.tiptap-content .tiptap p.is-editor-empty:first-child::before {
    color: hsl(var(--muted-foreground));
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
}
</style>
