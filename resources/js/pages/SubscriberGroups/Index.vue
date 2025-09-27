<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Edit, Eye, Trash2, Plus, Users, Mail } from 'lucide-vue-next';
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
import { 
    index as subscriberGroupsIndex, 
    create as subscriberGroupsCreate, 
    show as subscriberGroupsShow, 
    edit as subscriberGroupsEdit, 
    destroy as subscriberGroupsDestroy 
} from '@/routes/subscriber-groups';

interface SubscriberGroup {
    id: string;
    name: string;
    description: string | null;
    is_active: boolean;
    created_at: string;
    subscribers_count: number;
    campaigns_count: number;
}

interface Props {
    subscriberGroups: {
        data: SubscriberGroup[];
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
        title: 'Grupos de Assinantes',
        href: '#',
    },
];

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || 'all');

const applyFilters = () => {
    router.get(subscriberGroupsIndex().url, {
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

const deleteSubscriberGroup = (subscriberGroup: SubscriberGroup) => {
    router.delete(subscriberGroupsDestroy({ subscriber_group: subscriberGroup.id }).url);
};

const getStatusBadge = (subscriberGroup: SubscriberGroup) => {
    return subscriberGroup.is_active 
        ? { variant: 'default' as const, text: 'Ativo' }
        : { variant: 'secondary' as const, text: 'Inativo' };
};
</script>

<template>
    <Head title="Grupos de Assinantes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Grupos de Assinantes</h1>
                <Button as-child>
                    <Link :href="subscriberGroupsCreate().url">
                        <Plus class="h-4 w-4 mr-2" />
                        Criar Grupo
                    </Link>
                </Button>
            </div>

            <!-- Filters -->
            <div class="flex gap-4">
                <div class="flex-1 max-w-sm">
                    <Input
                        v-model="search"
                        placeholder="Buscar grupos..."
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
                    </SelectContent>
                </Select>
            </div>

            <!-- Empty State -->
            <div v-if="subscriberGroups.data.length === 0" class="flex flex-col items-center justify-center py-12">
                <div class="text-center">
                    <h3 class="text-lg font-semibold">Nenhum grupo encontrado</h3>
                    <p class="text-muted-foreground">
                        Comece criando seu primeiro grupo de assinantes.
                    </p>
                    <Button class="mt-4" as-child>
                        <Link :href="subscriberGroupsCreate().url">
                            Criar Primeiro Grupo
                        </Link>
                    </Button>
                </div>
            </div>

            <!-- Groups Grid -->
            <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card v-for="subscriberGroup in subscriberGroups.data" :key="subscriberGroup.id">
                    <CardHeader>
                        <div class="flex items-start justify-between">
                            <CardTitle class="text-lg">{{ subscriberGroup.name }}</CardTitle>
                            <Badge :variant="getStatusBadge(subscriberGroup).variant">
                                {{ getStatusBadge(subscriberGroup).text }}
                            </Badge>
                        </div>
                        <p v-if="subscriberGroup.description" class="text-sm text-muted-foreground">
                            {{ subscriberGroup.description }}
                        </p>
                        <p class="text-sm text-muted-foreground">
                            Criado em {{ new Date(subscriberGroup.created_at).toLocaleDateString('pt-BR') }}
                        </p>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col gap-4">
                            <!-- Stats -->
                            <div class="flex justify-between text-sm">
                                <div class="flex items-center gap-1">
                                    <Users class="h-4 w-4 text-muted-foreground" />
                                    <span>{{ subscriberGroup.subscribers_count }} assinantes</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <Mail class="h-4 w-4 text-muted-foreground" />
                                    <span>{{ subscriberGroup.campaigns_count }} campanhas</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-between gap-2">
                                <div class="flex gap-2">
                                    <Button variant="outline" size="sm" as-child>
                                        <Link :href="subscriberGroupsShow({ subscriber_group: subscriberGroup.id }).url">
                                            <Eye class="h-3 w-3 mr-1" />
                                            Ver
                                        </Link>
                                    </Button>
                                    <Button variant="outline" size="sm" as-child>
                                        <Link :href="subscriberGroupsEdit({ subscriber_group: subscriberGroup.id }).url">
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
                                                Tem certeza que deseja excluir o grupo "{{ subscriberGroup.name }}"?
                                                Esta ação não pode ser desfeita.
                                            </AlertDialogDescription>
                                        </AlertDialogHeader>
                                        <AlertDialogFooter>
                                            <AlertDialogCancel>Cancelar</AlertDialogCancel>
                                            <AlertDialogAction @click="deleteSubscriberGroup(subscriberGroup)">
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
            <div v-if="subscriberGroups.last_page > 1" class="flex justify-center mt-6">
                <div class="flex items-center gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="subscriberGroups.current_page === 1"
                        @click="router.get(subscriberGroupsIndex().url, { ...filters, page: subscriberGroups.current_page - 1 })"
                    >
                        Anterior
                    </Button>
                    <span class="text-sm text-muted-foreground">
                        Página {{ subscriberGroups.current_page }} de {{ subscriberGroups.last_page }}
                    </span>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="subscriberGroups.current_page === subscriberGroups.last_page"
                        @click="router.get(subscriberGroupsIndex().url, { ...filters, page: subscriberGroups.current_page + 1 })"
                    >
                        Próxima
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
