<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Edit, Trash2, Mail, User, Calendar, Users } from 'lucide-vue-next';
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
// Importar rotas necessárias
import { dashboard } from '@/routes';
import { index as subscribersIndex, edit as subscribersEdit, destroy as subscribersDestroy } from '@/routes/subscribers';

interface SubscriberGroup {
    id: string;
    name: string;
}

interface CampaignSend {
    id: string;
    campaign_id: string;
    sent_at: string;
    opened_at: string | null;
    clicked_at: string | null;
}

interface Subscriber {
    id: string;
    email: string;
    first_name: string | null;
    last_name: string | null;
    full_name: string | null;
    status: 'active' | 'inactive' | 'unsubscribed';
    subscribed_at: string;
    unsubscribed_at: string | null;
    subscriber_groups: SubscriberGroup[];
    campaign_sends: CampaignSend[];
    total_campaigns_sent: number;
    last_campaign_sent: string | null;
    created_at: string;
    metadata: Record<string, any>;
}

interface Props {
    subscriber: Subscriber;
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
    {
        title: props.subscriber.email,
        href: '#',
    },
];

const deleteSubscriber = () => {
    router.delete(subscribersDestroy({ subscriber: props.subscriber.id }).url, {
        onSuccess: () => {
            // Redirecionamento será feito pelo controller
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
    <Head :title="`Assinante - ${subscriber.email}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ subscriber.email }}</h1>
                    <p class="text-muted-foreground">
                        Detalhes do assinante
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="subscribersEdit({ subscriber: subscriber.id }).url">
                            <Edit class="mr-2 h-4 w-4" />
                            Editar
                        </Link>
                    </Button>
                    <AlertDialog>
                        <AlertDialogTrigger as-child>
                            <Button variant="destructive">
                                <Trash2 class="mr-2 h-4 w-4" />
                                Excluir
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
                                    @click="deleteSubscriber"
                                    class="bg-destructive text-destructive-foreground hover:bg-destructive/90"
                                >
                                    Excluir
                                </AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Informações Básicas -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <User class="h-5 w-5" />
                            Informações Básicas
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-center gap-3">
                            <Mail class="h-4 w-4 text-muted-foreground" />
                            <span class="font-medium">{{ subscriber.email }}</span>
                        </div>

                        <div v-if="subscriber.full_name" class="flex items-center gap-3">
                            <User class="h-4 w-4 text-muted-foreground" />
                            <span>{{ subscriber.full_name }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-sm text-muted-foreground">Status:</span>
                            <Badge :variant="getStatusBadgeVariant(subscriber.status)">
                                {{ getStatusLabel(subscriber.status) }}
                            </Badge>
                        </div>

                        <div class="flex items-center gap-3">
                            <Calendar class="h-4 w-4 text-muted-foreground" />
                            <span class="text-sm">
                                Inscrito em {{ new Date(subscriber.subscribed_at).toLocaleDateString('pt-BR') }}
                            </span>
                        </div>

                        <div v-if="subscriber.unsubscribed_at" class="flex items-center gap-3">
                            <Calendar class="h-4 w-4 text-muted-foreground" />
                            <span class="text-sm">
                                Cancelado em {{ new Date(subscriber.unsubscribed_at).toLocaleDateString('pt-BR') }}
                            </span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Grupos -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Users class="h-5 w-5" />
                            Grupos de Assinantes
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="subscriber.subscriber_groups.length > 0" class="flex flex-wrap gap-2">
                            <Badge
                                v-for="group in subscriber.subscriber_groups"
                                :key="group.id"
                                variant="outline"
                            >
                                {{ group.name }}
                            </Badge>
                        </div>
                        <div v-else class="text-sm text-muted-foreground">
                            Não pertence a nenhum grupo
                        </div>
                    </CardContent>
                </Card>

                <!-- Estatísticas de Campanhas -->
                <Card>
                    <CardHeader>
                        <CardTitle>Estatísticas de Campanhas</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Total de campanhas enviadas:</span>
                            <span class="font-medium">{{ subscriber.total_campaigns_sent }}</span>
                        </div>

                        <div v-if="subscriber.last_campaign_sent" class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Última campanha:</span>
                            <span class="text-sm">{{ subscriber.last_campaign_sent }}</span>
                        </div>

                        <div v-if="subscriber.total_campaigns_sent === 0" class="text-sm text-muted-foreground">
                            Nenhuma campanha enviada ainda
                        </div>
                    </CardContent>
                </Card>

                <!-- Metadados -->
                <Card v-if="Object.keys(subscriber.metadata).length > 0">
                    <CardHeader>
                        <CardTitle>Metadados</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div
                                v-for="(value, key) in subscriber.metadata"
                                :key="key"
                                class="flex items-center justify-between"
                            >
                                <span class="text-sm text-muted-foreground">{{ key }}:</span>
                                <span class="text-sm">{{ value }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Histórico de Campanhas -->
            <Card v-if="subscriber.campaign_sends.length > 0">
                <CardHeader>
                    <CardTitle>Histórico de Campanhas</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div
                            v-for="campaignSend in subscriber.campaign_sends"
                            :key="campaignSend.id"
                            class="flex items-center justify-between p-4 border rounded-lg"
                        >
                            <div>
                                <div class="font-medium">Campanha #{{ campaignSend.campaign_id }}</div>
                                <div class="text-sm text-muted-foreground">
                                    Enviada em {{ new Date(campaignSend.sent_at).toLocaleDateString('pt-BR') }}
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <Badge v-if="campaignSend.opened_at" variant="outline">
                                    Aberta
                                </Badge>
                                <Badge v-if="campaignSend.clicked_at" variant="outline">
                                    Clicada
                                </Badge>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>



