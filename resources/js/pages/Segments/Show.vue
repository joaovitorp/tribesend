<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Edit, Users, Mail } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { index as segmentsIndex, edit as segmentsEdit } from '@/routes/segments';

interface Segment {
    id: string;
    name: string;
    description: string | null;
    is_active: boolean;
    total_subscribers: number;
    total_campaigns: number;
    created_at: string;
    updated_at: string;
}

interface Props {
    segment: Segment;
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
    {
        title: props.segment.name,
        href: '',
    },
];
</script>

<template>
    <Head :title="`Segmento: ${segment.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ segment.name }}</h1>
                    <p class="text-sm text-muted-foreground">
                        {{ segment.description || 'Sem descrição' }}
                    </p>
                </div>
                <Button as-child>
                    <Link :href="segmentsEdit({ segment: segment.id }).url">
                        <Edit class="mr-2 h-4 w-4" />
                        Editar
                    </Link>
                </Button>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Status</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Badge :variant="segment.is_active ? 'default' : 'secondary'">
                            {{ segment.is_active ? 'Ativo' : 'Inativo' }}
                        </Badge>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Assinantes</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ segment.total_subscribers }}</div>
                        <p class="text-xs text-muted-foreground">
                            Total de assinantes
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Campanhas</CardTitle>
                        <Mail class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ segment.total_campaigns }}</div>
                        <p class="text-xs text-muted-foreground">
                            Total de campanhas
                        </p>
                    </CardContent>
                </Card>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Detalhes</CardTitle>
                    <CardDescription>Informações completas do segmento</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Nome</p>
                            <p class="text-sm">{{ segment.name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Status</p>
                            <Badge :variant="segment.is_active ? 'default' : 'secondary'">
                                {{ segment.is_active ? 'Ativo' : 'Inativo' }}
                            </Badge>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm font-medium text-muted-foreground">Descrição</p>
                            <p class="text-sm">{{ segment.description || 'Sem descrição' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Criado em</p>
                            <p class="text-sm">{{ new Date(segment.created_at).toLocaleDateString('pt-BR') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Atualizado em</p>
                            <p class="text-sm">{{ new Date(segment.updated_at).toLocaleDateString('pt-BR') }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

