<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import type { BreadcrumbItemType } from '@/types';
import { dashboard } from '@/routes';
import { index as campaignsIndex, edit as campaignsEdit, send as campaignsSend } from '@/routes/campaigns';
import { Mail, ArrowLeft, Send } from 'lucide-vue-next';

interface Segment {
    id: string;
    name: string;
}

interface Campaign {
    id: string;
    name: string;
    subject: string;
    body_html: string;
    status: 'draft' | 'scheduled' | 'sending' | 'sent' | 'canceled';
    scheduled_at: string | null;
    sent_at: string | null;
    included_segments: Segment[];
    excluded_segments: Segment[];
    total_recipients?: number;
}

interface Props {
    campaign: Campaign;
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
        title: 'Preview',
        href: '',
    },
];

const getStatusBadge = (status: Campaign['status']) => {
    const variants = {
        draft: { variant: 'secondary' as const, label: 'Rascunho' },
        scheduled: { variant: 'default' as const, label: 'Agendada' },
        sending: { variant: 'default' as const, label: 'Enviando' },
        sent: { variant: 'default' as const, label: 'Enviada' },
        canceled: { variant: 'destructive' as const, label: 'Cancelada' },
    };

    return variants[status];
};

const sendCampaign = () => {
    if (confirm('Tem certeza que deseja enviar esta campanha? Esta ação não pode ser desfeita.')) {
        router.post(campaignsSend({ campaign: props.campaign.id }).url);
    }
};
</script>

<template>
    <Head :title="`Preview: ${campaign.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ campaign.name }}</h1>
                    <p class="text-muted-foreground">
                        Preview da campanha
                    </p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <a :href="campaignsIndex().url">
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Voltar
                        </a>
                    </Button>
                    <Button
                        v-if="campaign.status === 'draft'"
                        variant="outline"
                        as-child
                    >
                        <a :href="campaignsEdit({ campaign: campaign.id }).url">
                            Editar
                        </a>
                    </Button>
                    <Button
                        v-if="campaign.status === 'draft' || campaign.status === 'scheduled'"
                        @click="sendCampaign"
                    >
                        <Send class="mr-2 h-4 w-4" />
                        Enviar Campanha
                    </Button>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-3">
                <!-- Campaign Info -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informações</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <p class="text-sm text-muted-foreground">Status</p>
                            <Badge :variant="getStatusBadge(campaign.status).variant">
                                {{ getStatusBadge(campaign.status).label }}
                            </Badge>
                        </div>

                        <div>
                            <p class="text-sm text-muted-foreground">Assunto</p>
                            <p class="font-medium">{{ campaign.subject }}</p>
                        </div>

                        <div v-if="campaign.total_recipients">
                            <p class="text-sm text-muted-foreground">Total de Destinatários</p>
                            <p class="font-medium">{{ campaign.total_recipients }}</p>
                        </div>

                        <div>
                            <p class="mb-2 text-sm text-muted-foreground">Segmentos Incluídos</p>
                            <div class="flex flex-wrap gap-2">
                                <Badge
                                    v-for="segment in campaign.included_segments"
                                    :key="segment.id"
                                    variant="secondary"
                                >
                                    {{ segment.name }}
                                </Badge>
                                <p v-if="campaign.included_segments.length === 0" class="text-sm text-muted-foreground">
                                    Nenhum
                                </p>
                            </div>
                        </div>

                        <div v-if="campaign.excluded_segments.length > 0">
                            <p class="mb-2 text-sm text-muted-foreground">Segmentos Excluídos</p>
                            <div class="flex flex-wrap gap-2">
                                <Badge
                                    v-for="segment in campaign.excluded_segments"
                                    :key="segment.id"
                                    variant="outline"
                                >
                                    {{ segment.name }}
                                </Badge>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Email Preview -->
                <Card class="lg:col-span-2">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Mail class="h-5 w-5" />
                            Preview do E-mail
                        </CardTitle>
                        <CardDescription>
                            Visualização de como o e-mail será exibido
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="rounded-lg border bg-background p-6">
                            <div class="mb-4 border-b pb-4">
                                <p class="text-sm text-muted-foreground">Assunto:</p>
                                <p class="text-lg font-semibold">{{ campaign.subject }}</p>
                            </div>
                            <div class="prose prose-sm max-w-none dark:prose-invert" v-html="campaign.body_html"></div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.prose :deep(p) {
    margin-bottom: 1rem;
}

.prose :deep(h1),
.prose :deep(h2),
.prose :deep(h3) {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.prose :deep(ul),
.prose :deep(ol) {
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}

.prose :deep(a) {
    color: hsl(var(--primary));
    text-decoration: underline;
}

.prose :deep(strong) {
    font-weight: 600;
}
</style>

