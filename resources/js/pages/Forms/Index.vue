<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Skeleton } from '@/components/ui/skeleton';
import { Copy, Edit, Eye, Trash2, Plus } from 'lucide-vue-next';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import type { BreadcrumbItem } from '@/types';
import { ref } from 'vue';
import { watchDebounced } from '@vueuse/core';
// Importar rotas necessárias
import { index as formsIndex, create as formsCreate, show as formsShow, edit as formsEdit, destroy as formsDestroy } from '@/routes/forms';
import { show as publicFormsShow } from '@/routes/public/forms';

interface Segment {
    id: string;
    name: string;
    description?: string;
}

interface Form {
    id: string;
    name: string;
    hash: string;
    is_active: boolean;
    expires_at: string | null;
    created_at: string;
    public_url?: string;
    segments?: string[];
    segment_details?: Segment[];
}

interface Props {
    forms: {
        data: Form[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    filters: {
        search?: string;
        status?: string;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Formulários',
        href: '#',
    },
];

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || 'all');

const applyFilters = () => {
    router.get(formsIndex().url, {
        search: search.value || undefined,
        status: status.value === 'all' ? undefined : status.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

watchDebounced([search, status], () => {
    applyFilters();
}, { debounce: 300 });

const deleteForm = (form: Form) => {
    router.delete(formsDestroy({ form: form.id }).url);
};

const copyUrl = async (url: string) => {
    try {
        await navigator.clipboard.writeText(url);
        // TODO: Add toast notification
    } catch (err) {
        console.error('Erro ao copiar URL:', err);
    }
};

const getStatusBadge = (form: Form) => {
    if (!form.is_active) {
        return { variant: 'secondary' as const, text: 'Inativo' };
    }
    
    if (form.expires_at && new Date(form.expires_at) < new Date()) {
        return { variant: 'destructive' as const, text: 'Expirado' };
    }
    
    return { variant: 'default' as const, text: 'Ativo' };
};
</script>

<template>
    <Head title="Formulários" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Formulários</h1>
                <Button as-child>
                    <Link :href="formsCreate().url">
                        <Plus class="h-4 w-4 mr-2" />
                        Criar Formulário
                    </Link>
                </Button>
            </div>

            <!-- Filters -->
            <div class="flex gap-4">
                <div class="flex-1 max-w-sm">
                    <Input
                        v-model="search"
                        placeholder="Buscar formulários..."
                        class="w-full"
                    />
                </div>
                <Select v-model="status">
                    <SelectTrigger class="w-48">
                        <SelectValue placeholder="Filtrar por status" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="all">Todos</SelectItem>
                        <SelectItem value="active">Ativo</SelectItem>
                        <SelectItem value="inactive">Inativo</SelectItem>
                        <SelectItem value="expired">Expirado</SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <!-- Empty State -->
            <div v-if="forms.data.length === 0" class="flex flex-col items-center justify-center py-12">
                <div class="text-center">
                    <h3 class="text-lg font-semibold">Nenhum formulário encontrado</h3>
                    <p class="text-muted-foreground">
                        Comece criando seu primeiro formulário de inscrição.
                    </p>
                    <Button class="mt-4" as-child>
                        <Link :href="formsCreate().url">
                            Criar Primeiro Formulário
                        </Link>
                    </Button>
                </div>
            </div>

            <!-- Forms Grid -->
            <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card v-for="form in forms.data" :key="form.id">
                    <CardHeader>
                        <div class="flex items-start justify-between">
                            <CardTitle class="text-lg">{{ form.name }}</CardTitle>
                            <Badge :variant="getStatusBadge(form).variant">
                                {{ getStatusBadge(form).text }}
                            </Badge>
                        </div>
                        <p class="text-sm text-muted-foreground">
                            Criado em {{ new Date(form.created_at).toLocaleDateString('pt-BR') }}
                        </p>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col gap-2">
                            <!-- Segmentos -->
                            <div v-if="form.segment_details && form.segment_details.length > 0" class="flex flex-wrap gap-1 mb-2">
                                <Badge 
                                    v-for="segment in form.segment_details" 
                                    :key="segment.id" 
                                    variant="outline"
                                    class="text-xs"
                                >
                                    {{ segment.name }}
                                </Badge>
                            </div>
                            

                            <!-- URL do formulário -->
                            <div class="flex items-center gap-2 p-2 bg-muted rounded text-sm">
                                <code class="flex-1 truncate">
                                    {{ publicFormsShow({ hash: form.hash }).url }}
                                </code>
                                <Button
                                    size="sm"
                                    variant="ghost"
                                    @click="copyUrl(publicFormsShow({ hash: form.hash }).url)"
                                >
                                    <Copy class="h-3 w-3" />
                                </Button>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-between gap-2 mt-4">
                                <div class="flex gap-2">
                                    <Button variant="outline" size="sm" as-child>
                                        <Link :href="formsShow({ form: form.id }).url">
                                            <Eye class="h-3 w-3 mr-1" />
                                            Ver
                                        </Link>
                                    </Button>
                                    <Button variant="outline" size="sm" as-child>
                                        <Link :href="formsEdit({ form: form.id }).url">
                                            <Edit class="h-3 w-3 mr-1" />
                                            Editar
                                        </Link>
                                    </Button>
                                </div>
                                <AlertDialog>
                                    <AlertDialogTrigger as-child>
                                        <Button 
                                            variant="destructive" 
                                            size="sm"
                                        >
                                            <Trash2 class="h-3 w-3" />
                                        </Button>
                                    </AlertDialogTrigger>
                                    <AlertDialogContent>
                                        <AlertDialogHeader>
                                            <AlertDialogTitle>Confirmar exclusão</AlertDialogTitle>
                                            <AlertDialogDescription>
                                                Tem certeza que deseja excluir o formulário "{{ form.name }}"?
                                                Esta ação não pode ser desfeita.
                                            </AlertDialogDescription>
                                        </AlertDialogHeader>
                                        <AlertDialogFooter>
                                            <AlertDialogCancel>Cancelar</AlertDialogCancel>
                                            <AlertDialogAction @click="deleteForm(form)">
                                                Excluir
                                            </AlertDialogAction>
                                        </AlertDialogFooter>
                                    </AlertDialogContent>
                                </AlertDialog>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Pagination -->
            <div v-if="forms.last_page > 1" class="flex justify-center mt-6">
                <div class="flex items-center gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="forms.current_page === 1"
                        @click="router.get(formsIndex().url, { ...filters, page: forms.current_page - 1 })"
                    >
                        Anterior
                    </Button>
                    <span class="text-sm text-muted-foreground">
                        Página {{ forms.current_page }} de {{ forms.last_page }}
                    </span>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="forms.current_page === forms.last_page"
                        @click="router.get(formsIndex().url, { ...filters, page: forms.current_page + 1 })"
                    >
                        Próxima
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
