<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Plus, Trash2 } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';
// Importar rotas necessárias
import { index as formsIndex, show as formsShow, update as formsUpdate } from '@/routes/forms';

interface Segment {
    id: string;
    name: string;
    description?: string;
}

interface FormField {
    name: string;
    type: string;
    label: string;
    required: boolean;
    options?: string[];
}

interface Form {
    id: string;
    name: string;
    segments: string[];
    fields: FormField[];
    referral?: string;
    content?: string;
    is_active: boolean;
    expires_at?: string;
}

interface Props {
    form: Form;
    segments: Segment[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Formulários',
        href: formsIndex().url,
    },
    {
        title: props.form.name,
        href: formsShow({ form: props.form.id }).url,
    },
    {
        title: 'Editar',
        href: '#',
    },
];

const form = useForm({
    name: props.form.name,
    segments: [...props.form.segments],
    fields: [...props.form.fields],
    referral: props.form.referral || '',
    content: props.form.content || '',
    is_active: props.form.is_active,
    expires_at: props.form.expires_at ? new Date(props.form.expires_at).toISOString().slice(0, 16) : '',
});

const fieldTypes = [
    { value: 'text', label: 'Texto' },
    { value: 'email', label: 'Email' },
    { value: 'phone', label: 'Telefone' },
    { value: 'textarea', label: 'Texto Longo' },
    { value: 'select', label: 'Seleção' },
    { value: 'checkbox', label: 'Checkbox' },
    { value: 'radio', label: 'Radio' },
];

const addField = () => {
    form.fields.push({
        name: '',
        type: 'text',
        label: '',
        required: false,
        options: []
    });
};

const removeField = (index: number) => {
    if (form.fields.length > 1) {
        form.fields.splice(index, 1);
    }
};

const addOption = (fieldIndex: number) => {
    if (!form.fields[fieldIndex].options) {
        form.fields[fieldIndex].options = [];
    }
    form.fields[fieldIndex].options!.push('');
};

const removeOption = (fieldIndex: number, optionIndex: number) => {
    form.fields[fieldIndex].options!.splice(optionIndex, 1);
};

const needsOptions = (type: string) => {
    return ['select', 'radio'].includes(type);
};

const submit = () => {
    form.put(formsUpdate({ form: props.form.id }).url, {
        onSuccess: () => {
            // Redirecionamento será feito pelo controller
        },
    });
};
</script>

<template>
    <Head :title="`Editar ${form.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Editar Formulário</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Informações Básicas -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informações Básicas</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="name">Nome do Formulário</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                :class="{ 'border-destructive': form.errors.name }"
                                placeholder="Ex: Newsletter Semanal"
                            />
                            <p v-if="form.errors.name" class="text-sm text-destructive">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="content">Descrição (opcional)</Label>
                            <Textarea
                                id="content"
                                v-model="form.content"
                                :class="{ 'border-destructive': form.errors.content }"
                                placeholder="Descrição que aparecerá no formulário público"
                                rows="3"
                            />
                            <p v-if="form.errors.content" class="text-sm text-destructive">
                                {{ form.errors.content }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="referral">Referência (opcional)</Label>
                            <Input
                                id="referral"
                                v-model="form.referral"
                                :class="{ 'border-destructive': form.errors.referral }"
                                placeholder="Ex: blog-post-1, campanha-facebook"
                            />
                            <p v-if="form.errors.referral" class="text-sm text-destructive">
                                {{ form.errors.referral }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="expires_at">Data de Expiração (opcional)</Label>
                                <Input
                                    id="expires_at"
                                    type="datetime-local"
                                    v-model="form.expires_at"
                                    :class="{ 'border-destructive': form.errors.expires_at }"
                                />
                                <p v-if="form.errors.expires_at" class="text-sm text-destructive">
                                    {{ form.errors.expires_at }}
                                </p>
                            </div>

                            <div class="flex items-center space-x-2 mt-8">
                                <Checkbox
                                    id="is_active"
                                    :checked="form.is_active"
                                    @update:checked="form.is_active = $event"
                                />
                                <Label for="is_active">Formulário ativo</Label>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Segmentos -->
                <Card>
                    <CardHeader>
                        <CardTitle>Segmentos</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <Label>Selecione os segmentos que receberão os inscritos</Label>
                            <div class="grid grid-cols-2 gap-4">
                                <div v-for="segment in segments" :key="segment.id" class="flex items-center space-x-2">
                                    <Checkbox
                                        :id="`segment-${segment.id}`"
                                        :checked="form.segments.includes(segment.id)"
                                        @update:checked="(checked) => {
                                            if (checked) {
                                                form.segments.push(segment.id);
                                            } else {
                                                const index = form.segments.indexOf(segment.id);
                                                if (index > -1) form.segments.splice(index, 1);
                                            }
                                        }"
                                    />
                                    <Label :for="`segment-${segment.id}`" class="text-sm">
                                        {{ segment.name }}
                                        <span v-if="segment.description" class="text-muted-foreground">
                                            - {{ segment.description }}
                                        </span>
                                    </Label>
                                </div>
                            </div>
                            <p v-if="form.errors.segments" class="text-sm text-destructive">
                                {{ form.errors.segments }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Campos do Formulário -->
                <Card>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle>Campos do Formulário</CardTitle>
                            <Button type="button" variant="outline" @click="addField">
                                <Plus class="h-4 w-4 mr-2" />
                                Adicionar Campo
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div v-for="(field, index) in form.fields" :key="index" class="border rounded-lg p-4 space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="font-medium">Campo {{ index + 1 }}</h4>
                                <Button
                                    v-if="form.fields.length > 1"
                                    type="button"
                                    variant="ghost"
                                    size="sm"
                                    @click="removeField(index)"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label :for="`field-name-${index}`">Nome do Campo</Label>
                                    <Input
                                        :id="`field-name-${index}`"
                                        v-model="field.name"
                                        placeholder="Ex: telefone, empresa"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <Label :for="`field-type-${index}`">Tipo</Label>
                                    <Select v-model="field.type">
                                        <SelectTrigger>
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="type in fieldTypes" :key="type.value" :value="type.value">
                                                {{ type.label }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label :for="`field-label-${index}`">Rótulo</Label>
                                    <Input
                                        :id="`field-label-${index}`"
                                        v-model="field.label"
                                        placeholder="Ex: Seu telefone"
                                    />
                                </div>

                                <div class="flex items-center space-x-2 mt-8">
                                    <Checkbox
                                        :id="`field-required-${index}`"
                                        :checked="field.required"
                                        @update:checked="field.required = $event"
                                    />
                                    <Label :for="`field-required-${index}`">Campo obrigatório</Label>
                                </div>
                            </div>

                            <!-- Opções para select/radio -->
                            <div v-if="needsOptions(field.type)" class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <Label>Opções</Label>
                                    <Button type="button" variant="outline" size="sm" @click="addOption(index)">
                                        <Plus class="h-3 w-3 mr-1" />
                                        Adicionar Opção
                                    </Button>
                                </div>
                                <div v-for="(option, optionIndex) in field.options" :key="optionIndex" class="flex gap-2">
                                    <Input
                                        v-model="field.options![optionIndex]"
                                        placeholder="Texto da opção"
                                        class="flex-1"
                                    />
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="sm"
                                        @click="removeOption(index, optionIndex)"
                                    >
                                        <Trash2 class="h-3 w-3" />
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Submit -->
                <div class="flex justify-end gap-4">
                    <Button type="button" variant="outline" @click="router.visit(formsIndex().url)">
                        Cancelar
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
