<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { BreadcrumbItemType } from '@/types';
import { dashboard } from '@/routes';
import { index as campaignsIndex } from '@/routes/campaigns';
import { ArrowLeft, Mail, Check, XCircle, Clock } from 'lucide-vue-next';

interface Subscriber {
    id: string;
    email: string;
    name: string | null;
}

interface CampaignSend {
    id: string;
    subscriber: Subscriber;
    status: string;
    sent_at: string | null;
    opened_at: string | null;
    clicked_at: string | null;
    bounced_at: string | null;
    bounce_reason: string | null;
}

interface Campaign {
    id: string;
    name: string;
    subject: string;
}

interface Props {
    campaign: Campaign;
    sends: {
        data: CampaignSend[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItemType[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Campanhas',
        href: campaignsIndex().url,
    },
    {
        title: 'Envios',
        href: '',
    },
];

const formatDate = (date: string | null) => {
    if (!date) {
        return '-';
    }
    
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getStatusIcon = (send: CampaignSend) => {
    if (send.bounced_at) {
        return { icon: XCircle, class: 'text-destructive', label: 'Bounced' };
    }
    
    if (send.clicked_at) {
        return { icon: Check, class: 'text-green-600', label: 'Clicado' };
    }
    
    if (send.opened_at) {
        return { icon: Mail, class: 'text-blue-600', label: 'Aberto' };
    }
    
    if (send.sent_at) {
        return { icon: Check, class: 'text-muted-foreground', label: 'Enviado' };
    }
    
    return { icon: Clock, class: 'text-muted-foreground', label: 'Pendente' };
};
</script>

<template>
    <Head title="Envios da Campanha" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ campaign.name }}</h1>
                    <p class="text-muted-foreground">
                        Histórico de envios desta campanha
                    </p>
                </div>
                <Button variant="outline" as-child>
                    <a :href="campaignsIndex().url">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Voltar
                    </a>
                </Button>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-4">
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">
                            Total de Envios
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-bold">{{ sends.total }}</p>
                    </CardContent>
                </Card>
                
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">
                            Enviados
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-bold">
                            {{ sends.data.filter(s => s.sent_at).length }}
                        </p>
                    </CardContent>
                </Card>
                
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">
                            Abertos
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-bold">
                            {{ sends.data.filter(s => s.opened_at).length }}
                        </p>
                    </CardContent>
                </Card>
                
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">
                            Cliques
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-bold">
                            {{ sends.data.filter(s => s.clicked_at).length }}
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Sends Table -->
            <Card>
                <CardHeader>
                    <CardTitle>Histórico de Envios</CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="sends.data.length === 0" class="flex flex-col items-center justify-center py-12">
                        <div class="text-center">
                            <Mail class="mx-auto h-12 w-12 text-muted-foreground" />
                            <h3 class="mt-4 text-lg font-semibold">Nenhum envio registrado</h3>
                            <p class="text-muted-foreground">
                                Esta campanha ainda não foi enviada.
                            </p>
                        </div>
                    </div>

                    <Table v-else>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Status</TableHead>
                                <TableHead>Assinante</TableHead>
                                <TableHead>E-mail</TableHead>
                                <TableHead>Enviado</TableHead>
                                <TableHead>Aberto</TableHead>
                                <TableHead>Clicado</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="send in sends.data" :key="send.id">
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <component 
                                            :is="getStatusIcon(send).icon" 
                                            class="h-4 w-4"
                                            :class="getStatusIcon(send).class"
                                        />
                                        <span class="text-sm">{{ getStatusIcon(send).label }}</span>
                                    </div>
                                </TableCell>
                                <TableCell class="font-medium">
                                    {{ send.subscriber.name || '-' }}
                                </TableCell>
                                <TableCell>
                                    {{ send.subscriber.email }}
                                </TableCell>
                                <TableCell>{{ formatDate(send.sent_at) }}</TableCell>
                                <TableCell>{{ formatDate(send.opened_at) }}</TableCell>
                                <TableCell>{{ formatDate(send.clicked_at) }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

