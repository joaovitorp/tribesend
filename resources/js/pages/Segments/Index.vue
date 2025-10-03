<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
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
import { dashboard } from '@/routes';
import { index as segmentsIndex, create as segmentsCreate, show as segmentsShow, edit as segmentsEdit, destroy as segmentsDestroy } from '@/routes/segments';

interface Segment {
    id: string;
    name: string;
    description: string | null;
    is_active: boolean;
    subscribers_count: number;
    campaigns_count: number;
    created_at: string;
}

interface Props {
    segments: {
        data: Segment[];
        current_page: number;
        per_page: number;
        total: number;
        last_page: number;
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
        title: 'Segmentos',
        href: segmentsIndex().url,
    },
];

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || 'all');

watchDebounced(
    [search, status],
    () => {
        router.get(
            segmentsIndex().url,
            {
                search: search.value || undefined,
                status: status.value !== 'all' ? status.value : undefined,
            },
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    },
    { debounce: 300 }
);

const deleteSegment = (id: string) => {
    router.delete(segmentsDestroy({ segment: id }).url);
};
</script>

<template>
    <Head title="Segmentos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Segmentos</h1>
                    <p class="text-sm text-muted-foreground">
                        Gerencie segmentos de assinantes
                    </p>
                </div>
                <Button as-child>
                    <Link :href="segmentsCreate().url">
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Segmento
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Filtros</CardTitle>
                    <CardDescription>Filtre os segmentos por nome ou status</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <Input
                                v-model="search"
                                placeholder="Buscar segmentos..."
                                class="w-full"
                            />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Lista de Segmentos</CardTitle>
                    <CardDescription>
                        {{ segments.total }} segmento(s) encontrado(s)
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table v-if="segments.data.length > 0">
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nome</TableHead>
                                <TableHead>Descrição</TableHead>
                                <TableHead>Assinantes</TableHead>
                                <TableHead>Campanhas</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-right">Ações</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="segment in segments.data" :key="segment.id">
                                <TableCell class="font-medium">{{ segment.name }}</TableCell>
                                <TableCell>{{ segment.description || '-' }}</TableCell>
                                <TableCell>
                                    <Badge variant="secondary">
                                        <Users class="mr-1 h-3 w-3" />
                                        {{ segment.subscribers_count }}
                                    </Badge>
                                </TableCell>
                                <TableCell>{{ segment.campaigns_count }}</TableCell>
                                <TableCell>
                                    <Badge :variant="segment.is_active ? 'default' : 'secondary'">
                                        {{ segment.is_active ? 'Ativo' : 'Inativo' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <Button variant="ghost" size="sm" as-child>
                                            <Link :href="segmentsShow({ segment: segment.id }).url">
                                                <Eye class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button variant="ghost" size="sm" as-child>
                                            <Link :href="segmentsEdit({ segment: segment.id }).url">
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <AlertDialog>
                                            <AlertDialogTrigger as-child>
                                                <Button variant="ghost" size="sm">
                                                    <Trash2 class="h-4 w-4 text-destructive" />
                                                </Button>
                                            </AlertDialogTrigger>
                                            <AlertDialogContent>
                                                <AlertDialogHeader>
                                                    <AlertDialogTitle>Confirmar exclusão</AlertDialogTitle>
                                                    <AlertDialogDescription>
                                                        Tem certeza que deseja excluir este segmento? Esta ação não pode ser desfeita.
                                                    </AlertDialogDescription>
                                                </AlertDialogHeader>
                                                <AlertDialogFooter>
                                                    <AlertDialogCancel>Cancelar</AlertDialogCancel>
                                                    <AlertDialogAction
                                                        @click="deleteSegment(segment.id)"
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
                    <div v-else class="py-8 text-center text-muted-foreground">
                        Nenhum segmento encontrado
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

