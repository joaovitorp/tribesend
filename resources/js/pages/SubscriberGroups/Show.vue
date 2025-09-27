<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Edit, Users, Mail, Calendar } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';

// Importar rotas necessárias
import { index as subscriberGroupsIndex, edit as subscriberGroupsEdit } from '@/routes/subscriber-groups';

interface SubscriberGroup {
    id: string;
    name: string;
    description: string | null;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    total_subscribers: number;
    total_campaigns: number;
    active_campaigns: number;
}

interface Props {
    subscriberGroup: SubscriberGroup;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Grupos de Assinantes',
        href: subscriberGroupsIndex().url,
    },
    {
        title: props.subscriberGroup.name,
        href: '#',
    },
];

const getStatusBadge = (subscriberGroup: SubscriberGroup) => {
    return subscriberGroup.is_active 
        ? { variant: 'default', text: 'Ativo' }
        : { variant: 'secondary', text: 'Inativo' };
};
</script>

<template>
    <Head :title="subscriberGroup.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="max-w-4xl mx-auto w-full space-y-6">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <h1 class="text-2xl font-semibold">{{ subscriberGroup.name }}</h1>
                        <Badge :variant="getStatusBadge(subscriberGroup).variant">
                            {{ getStatusBadge(subscriberGroup).text }}
                        </Badge>
                    </div>
                    <Button as-child>
                        <Link :href="subscriberGroupsEdit({ subscriber_group: subscriberGroup.id }).url">
                            <Edit class="h-4 w-4 mr-2" />
                            Editar Grupo
                        </Link>
                    </Button>
                </div>

                <!-- Informações Básicas -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informações do Grupo</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div v-if="subscriberGroup.description">
                            <h4 class="font-medium text-sm text-muted-foreground mb-2">Descrição</h4>
                            <p class="text-sm">{{ subscriberGroup.description }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center gap-2">
                                <Calendar class="h-4 w-4 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">Criado em</p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ new Date(subscriberGroup.created_at).toLocaleDateString('pt-BR', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        }) }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <Calendar class="h-4 w-4 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">Última atualização</p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ new Date(subscriberGroup.updated_at).toLocaleDateString('pt-BR', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        }) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Estatísticas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <Card>
                        <CardContent class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                    <Users class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">{{ subscriberGroup.total_subscribers }}</p>
                                    <p class="text-sm text-muted-foreground">Assinantes</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardContent class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                    <Mail class="h-5 w-5 text-green-600 dark:text-green-400" />
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">{{ subscriberGroup.total_campaigns }}</p>
                                    <p class="text-sm text-muted-foreground">Total de Campanhas</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardContent class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                                    <Mail class="h-5 w-5 text-orange-600 dark:text-orange-400" />
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">{{ subscriberGroup.active_campaigns }}</p>
                                    <p class="text-sm text-muted-foreground">Campanhas Ativas</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Ações Rápidas -->
                <Card>
                    <CardHeader>
                        <CardTitle>Ações Rápidas</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <Button variant="outline" class="justify-start h-auto p-4" disabled>
                                <Users class="h-5 w-5 mr-3" />
                                <div class="text-left">
                                    <p class="font-medium">Gerenciar Assinantes</p>
                                    <p class="text-sm text-muted-foreground">Ver e gerenciar assinantes deste grupo</p>
                                </div>
                            </Button>

                            <Button variant="outline" class="justify-start h-auto p-4" disabled>
                                <Mail class="h-5 w-5 mr-3" />
                                <div class="text-left">
                                    <p class="font-medium">Criar Campanha</p>
                                    <p class="text-sm text-muted-foreground">Enviar campanha para este grupo</p>
                                </div>
                            </Button>
                        </div>
                        <p class="text-sm text-muted-foreground mt-4">
                            * Funcionalidades em desenvolvimento
                        </p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
