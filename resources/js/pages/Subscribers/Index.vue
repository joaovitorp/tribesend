<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Edit, Trash2, Plus, Users, Eye } from 'lucide-vue-next';
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
import { dashboard } from '@/routes';
import { index as subscribersIndex, create as subscribersCreate, show as subscribersShow, edit as subscribersEdit, destroy as subscribersDestroy } from '@/routes/subscribers';

interface Subscriber {
    id: string;
    email: string;
    first_name: string | null;
    last_name: string | null;
    full_name: string | null;
    status: 'active' | 'inactive' | 'unsubscribed';
    subscribed_at: string;
    unsubscribed_at: string | null;
    created_at: string;
}

interface Props {
    subscribers: {
        data: Subscriber[];
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
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Assinantes',
        href: subscribersIndex().url,
    },
];

// Filtros reativos
const search = ref(props.filters.search || '');
const status = ref(props.filters.status || 'all');

// Aplicar filtros com debounce
watchDebounced([search, status], () => {
    router.get(subscribersIndex().url, {
        search: search.value || undefined,
        status: status.value === 'all' ? undefined : status.value,
    }, {
        preserveState: true,
        replace: true,
    });
}, { debounce: 300 });

const deleteSubscriber = (subscriber: Subscriber) => {
    router.delete(subscribersDestroy({ subscriber: subscriber.id }).url, {
        onSuccess: () => {
            // Sucesso será mostrado via flash message
        },
    });
};

const getStatusBadgeVariant = (status: string) => {
    switch (status) {
        case 'active':
            return 'default';
        case 'inactive':
            return 'secondary';
        case 'unsubscribed':
            return 'destructive';
        default:
            return 'secondary';
    }
};

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'active':
            return 'Ativo';
        case 'inactive':
            return 'Inativo';
        case 'unsubscribed':
            return 'Cancelado';
        default:
            return status;
    }
};
</script>

<template>
    <Head title="Assinantes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Assinantes</h1>
                    <p class="text-muted-foreground">
                        Gerencie seus assinantes e suas informações
                    </p>
                </div>
                <Button as-child>
                    <Link :href="subscribersCreate().url">
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Assinante
                    </Link>
                </Button>
            </div>

            <!-- Filtros -->
            <Card>
                <CardContent class="pt-6">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <Input
                                v-model="search"
                                placeholder="Buscar por email ou nome..."
                                class="w-full"
                            />
                        </div>
                        <div class="space-y-2">
                            <Select v-model="status">
                                <SelectTrigger>
                                    <SelectValue placeholder="Todos os status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">Todos os status</SelectItem>
                                    <SelectItem value="active">Ativo</SelectItem>
                                    <SelectItem value="inactive">Inativo</SelectItem>
                                    <SelectItem value="unsubscribed">Cancelado</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Tabela de Assinantes -->
            <Card>
                <CardContent class="p-0">
                    <!-- Empty State -->
                    <div v-if="subscribers.data.length === 0" class="flex flex-col items-center justify-center py-12">
                        <Users class="h-12 w-12 text-muted-foreground mb-4" />
                        <div class="text-center">
                            <h3 class="text-lg font-semibold">Nenhum assinante encontrado</h3>
                            <p class="text-muted-foreground mb-4">
                                {{ search || status ? 'Tente ajustar os filtros ou' : '' }}
                                Comece adicionando seu primeiro assinante.
                            </p>
                            <Button as-child>
                                <Link :href="subscribersCreate().url">
                                    <Plus class="mr-2 h-4 w-4" />
                                    Adicionar Assinante
                                </Link>
                            </Button>
                        </div>
                    </div>

                    <!-- Tabela -->
                    <Table v-else>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Email</TableHead>
                                <TableHead>Nome</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Data de Inscrição</TableHead>
                                <TableHead class="text-right">Ações</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="subscriber in subscribers.data" :key="subscriber.id">
                                <TableCell class="font-medium">
                                    {{ subscriber.email }}
                                </TableCell>
                                <TableCell>
                                    {{ subscriber.full_name || '-' }}
                                </TableCell>
                                <TableCell>
                                    <Badge :variant="getStatusBadgeVariant(subscriber.status)">
                                        {{ getStatusLabel(subscriber.status) }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    {{ new Date(subscriber.subscribed_at).toLocaleDateString('pt-BR') }}
                                </TableCell>
                                <TableCell class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Button variant="ghost" size="sm" as-child>
                                            <Link :href="subscribersShow({ subscriber: subscriber.id }).url">
                                                <Eye class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button variant="ghost" size="sm" as-child>
                                            <Link :href="subscribersEdit({ subscriber: subscriber.id }).url">
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <AlertDialog>
                                            <AlertDialogTrigger as-child>
                                                <Button variant="ghost" size="sm">
                                                    <Trash2 class="h-4 w-4" />
                                                </Button>
                                            </AlertDialogTrigger>
                                            <AlertDialogContent>
                                                <AlertDialogHeader>
                                                    <AlertDialogTitle>Excluir Assinante</AlertDialogTitle>
                                                    <AlertDialogDescription>
                                                        Tem certeza que deseja excluir o assinante {{ subscriber.email }}?
                                                        Esta ação não pode ser desfeita.
                                                    </AlertDialogDescription>
                                                </AlertDialogHeader>
                                                <AlertDialogFooter>
                                                    <AlertDialogCancel>Cancelar</AlertDialogCancel>
                                                    <AlertDialogAction
                                                        @click="deleteSubscriber(subscriber)"
                                                        class="bg-destructive text-destructive-foreground hover:bg-destructive/90"
                                                    >
                                                        Excluir
                                                    </AlertDialogAction>
                                                </AlertDialogFooter>
                                            </AlertDialogContent>
                                        </AlertDialog>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Paginação -->
                    <div v-if="subscribers.last_page > 1" class="flex items-center justify-between p-4 border-t">
                        <div class="text-sm text-muted-foreground">
                            Mostrando {{ ((subscribers.current_page - 1) * subscribers.per_page) + 1 }} a 
                            {{ Math.min(subscribers.current_page * subscribers.per_page, subscribers.total) }} 
                            de {{ subscribers.total }} assinantes
                        </div>
                        <div class="flex items-center gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="subscribers.current_page === 1"
                                @click="router.get(subscribersIndex().url, { 
                                    search: search || undefined,
                                    status: status === 'all' ? undefined : status,
                                    page: subscribers.current_page - 1 
                                })"
                            >
                                Anterior
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="subscribers.current_page === subscribers.last_page"
                                @click="router.get(subscribersIndex().url, { 
                                    search: search || undefined,
                                    status: status === 'all' ? undefined : status,
                                    page: subscribers.current_page + 1 
                                })"
                            >
                                Próxima
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
