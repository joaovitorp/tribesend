<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Copy, Edit, ExternalLink } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';
// Importar rotas necessárias
import { index as formsIndex, edit as formsEdit } from '@/routes/forms';

interface FormField {
    name: string;
    type: string;
    label: string;
    required: boolean;
    options?: string[];
}

interface Segment {
    id: string;
    name: string;
    description?: string;
}

interface Form {
    id: string;
    name: string;
    hash: string;
    segments: string[];
    segment_details?: Segment[];
    fields: FormField[];
    referral?: string;
    content?: string;
    is_active: boolean;
    expires_at?: string;
    created_at: string;
    updated_at: string;
    public_url: string;
    is_expired: boolean;
    is_valid: boolean;
}

interface Props {
    form: Form;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Formulários',
        href: formsIndex().url,
    },
    {
        title: props.form.name,
        href: '#',
    },
];

const copyUrl = async (url: string) => {
    try {
        await navigator.clipboard.writeText(url);
        // TODO: Add toast notification
    } catch (err) {
        console.error('Erro ao copiar URL:', err);
    }
};

const getStatusBadge = () => {
    if (!props.form.is_active) {
        return { variant: 'secondary', text: 'Inativo' };
    }
    
    if (props.form.is_expired) {
        return { variant: 'destructive', text: 'Expirado' };
    }
    
    return { variant: 'default', text: 'Ativo' };
};

const getFieldTypeLabel = (type: string) => {
    const types: Record<string, string> = {
        text: 'Texto',
        email: 'Email',
        phone: 'Telefone',
        textarea: 'Texto Longo',
        select: 'Seleção',
        checkbox: 'Checkbox',
        radio: 'Radio',
    };
    return types[type] || type;
};
</script>

<template>
    <Head :title="form.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h1 class="text-2xl font-semibold">{{ form.name }}</h1>
                    <Badge :variant="getStatusBadge().variant">
                        {{ getStatusBadge().text }}
                    </Badge>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="form.public_url" target="_blank">
                            <ExternalLink class="h-4 w-4 mr-2" />
                            Ver Formulário
                        </Link>
                    </Button>
                    <Button as-child>
                        <Link :href="formsEdit({ form: form.id }).url">
                            <Edit class="h-4 w-4 mr-2" />
                            Editar
                        </Link>
                    </Button>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Informações Gerais -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informações Gerais</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <h4 class="font-medium text-sm text-muted-foreground">Nome</h4>
                            <p>{{ form.name }}</p>
                        </div>

                        <div v-if="form.content">
                            <h4 class="font-medium text-sm text-muted-foreground">Descrição</h4>
                            <p class="whitespace-pre-wrap">{{ form.content }}</p>
                        </div>

                        <div v-if="form.referral">
                            <h4 class="font-medium text-sm text-muted-foreground">Referência</h4>
                            <p>{{ form.referral }}</p>
                        </div>

                        <div>
                            <h4 class="font-medium text-sm text-muted-foreground">Status</h4>
                            <div class="flex items-center gap-2">
                                <Badge :variant="getStatusBadge().variant">
                                    {{ getStatusBadge().text }}
                                </Badge>
                                <span v-if="form.expires_at" class="text-sm text-muted-foreground">
                                    Expira em {{ new Date(form.expires_at).toLocaleDateString('pt-BR') }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-sm text-muted-foreground">Criado em</h4>
                            <p>{{ new Date(form.created_at).toLocaleDateString('pt-BR') }}</p>
                        </div>

                        <div>
                            <h4 class="font-medium text-sm text-muted-foreground">Última atualização</h4>
                            <p>{{ new Date(form.updated_at).toLocaleDateString('pt-BR') }}</p>
                        </div>
                    </CardContent>
                </Card>

                <!-- URL Pública -->
                <Card>
                    <CardHeader>
                        <CardTitle>URL Pública</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <p class="text-sm text-muted-foreground">
                                Use esta URL para compartilhar o formulário de inscrição:
                            </p>
                            
                            <div class="flex items-center gap-2 p-3 bg-muted rounded-lg">
                                <code class="flex-1 text-sm break-all">
                                    {{ form.public_url }}
                                </code>
                                <Button
                                    size="sm"
                                    variant="ghost"
                                    @click="copyUrl(form.public_url)"
                                >
                                    <Copy class="h-4 w-4" />
                                </Button>
                            </div>

                            <div class="flex gap-2">
                                <Button variant="outline" size="sm" as-child>
                                    <Link :href="form.public_url" target="_blank">
                                        <ExternalLink class="h-3 w-3 mr-1" />
                                        Abrir Formulário
                                    </Link>
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Campos do Formulário -->
            <Card>
                <CardHeader>
                    <CardTitle>Campos do Formulário</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div v-for="(field, index) in form.fields" :key="index" class="border rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium">{{ field.label }}</h4>
                                <div class="flex items-center gap-2">
                                    <Badge variant="outline">{{ getFieldTypeLabel(field.type) }}</Badge>
                                    <Badge v-if="field.required" variant="secondary" class="text-xs">
                                        Obrigatório
                                    </Badge>
                                </div>
                            </div>
                            
                            <div class="text-sm text-muted-foreground">
                                <p><strong>Nome do campo:</strong> {{ field.name }}</p>
                                
                                <div v-if="field.options && field.options.length > 0" class="mt-2">
                                    <strong>Opções:</strong>
                                    <ul class="list-disc list-inside ml-4">
                                        <li v-for="option in field.options" :key="option">{{ option }}</li>
                                    </ul>
                                </div>
                            </div>
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
                        <p class="text-sm text-muted-foreground">
                            Os inscritos serão adicionados aos seguintes segmentos:
                        </p>
                        <div v-if="form.segment_details && form.segment_details.length > 0" class="flex flex-wrap gap-2">
                            <Badge v-for="segment in form.segment_details" :key="segment.id" variant="outline">
                                {{ segment.name }}
                                <span v-if="segment.description" class="ml-1 text-muted-foreground text-xs">
                                    ({{ segment.description }})
                                </span>
                            </Badge>
                        </div>
                        <p v-else class="text-sm text-muted-foreground">
                            Nenhum segmento configurado
                        </p>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
