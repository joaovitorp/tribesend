<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button, buttonVariants } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
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
import type { BreadcrumbItemType } from '@/types';
import { dashboard } from '@/routes';
import { 
    index as campaignsIndex, 
    create as campaignsCreate, 
    edit as campaignsEdit,
    destroy as campaignsDestroy,
    preview as campaignsPreview,
    sends as campaignsSends
} from '@/routes/campaigns';
import { Mail, MoreVertical, Eye, SquarePen, Trash2, Send, List } from 'lucide-vue-next';
import { ref } from 'vue';

interface Campaign {
    id: string;
    name: string;
    subject: string;
    status: 'draft' | 'scheduled' | 'sending' | 'sent' | 'canceled';
    scheduled_at: string | null;
    sent_at: string | null;
    recipients_count: number;
    sent_count: number;
    opened_count: number;
    clicked_count: number;
    created_at: string;
}

interface Props {
    campaigns: {
        data: Campaign[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    filters?: {
        search?: string;
        status?: string;
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
];

const search = ref(props.filters?.search || '');

const performSearch = () => {
    router.get(campaignsIndex().url, { search: search.value }, { preserveState: true });
};

const deleteCampaign = (id: string) => {
    router.delete(campaignsDestroy({ campaign: id }).url);
};

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
</script>

<template>
    <Head title="Campanhas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Campanhas</h1>
                    <p class="text-muted-foreground">
                        Gerencie e envie newsletters para seus assinantes
                    </p>
                </div>
                <Button as-child>
                    <Link :href="campaignsCreate().url">
                        <Mail class="mr-2 h-4 w-4" />
                        Nova Campanha
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <CardTitle>Todas as Campanhas</CardTitle>
                        <div class="w-72">
                            <form @submit.prevent="performSearch">
                                <Input
                                    v-model="search"
                                    placeholder="Buscar campanhas..."
                                    @input="performSearch"
                                />
                            </form>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div v-if="campaigns.data.length === 0" class="flex flex-col items-center justify-center py-12">
                        <div class="text-center">
                            <Mail class="mx-auto h-12 w-12 text-muted-foreground" />
                            <h3 class="mt-4 text-lg font-semibold">Nenhuma campanha encontrada</h3>
                            <p class="text-muted-foreground">
                                Comece criando sua primeira campanha.
                            </p>
                            <Button class="mt-4" as-child>
                                <Link :href="campaignsCreate().url">
                                    Criar Primeira Campanha
                                </Link>
                            </Button>
                        </div>
                    </div>

                    <div v-else>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Nome</TableHead>
                                    <TableHead>Assunto</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Enviados</TableHead>
                                    <TableHead>Aberturas</TableHead>
                                    <TableHead>Cliques</TableHead>
                                    <TableHead>Data</TableHead>
                                    <TableHead class="text-right">Ações</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="campaign in campaigns.data" :key="campaign.id">
                                    <TableCell class="font-medium">{{ campaign.name }}</TableCell>
                                    <TableCell>{{ campaign.subject }}</TableCell>
                                    <TableCell>
                                        <Badge :variant="getStatusBadge(campaign.status).variant">
                                            {{ getStatusBadge(campaign.status).label }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>{{ campaign.sent_count }} / {{ campaign.recipients_count }}</TableCell>
                                    <TableCell>{{ campaign.opened_count }}</TableCell>
                                    <TableCell>{{ campaign.clicked_count }}</TableCell>
                                    <TableCell>{{ formatDate(campaign.sent_at || campaign.created_at) }}</TableCell>
                                    <TableCell class="text-right">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button variant="ghost" size="sm">
                                                    <MoreVertical class="h-4 w-4" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end">
                                                <DropdownMenuItem as-child>
                                                    <Link :href="campaignsPreview({ campaign: campaign.id }).url">
                                                        <Eye class="mr-2 h-4 w-4" />
                                                        Preview
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem 
                                                    v-if="campaign.status === 'draft' || campaign.status === 'scheduled'"
                                                    as-child
                                                >
                                                    <Link :href="campaignsEdit({ campaign: campaign.id }).url">
                                                        <SquarePen class="mr-2 h-4 w-4" />
                                                        Editar
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem 
                                                    v-if="campaign.status === 'sent'"
                                                    as-child
                                                >
                                                    <Link :href="campaignsSends({ campaign: campaign.id }).url">
                                                        <List class="mr-2 h-4 w-4" />
                                                        Ver Envios
                                                    </Link>
                                                </DropdownMenuItem>
                                                <AlertDialog v-if="campaign.status === 'draft'">
                                                    <AlertDialogTrigger as-child>
                                                        <DropdownMenuItem
                                                            class="text-destructive"
                                                            @select.prevent
                                                        >
                                                            <Trash2 class="mr-2 h-4 w-4" />
                                                            Excluir
                                                        </DropdownMenuItem>
                                                    </AlertDialogTrigger>
                                                    <AlertDialogContent>
                                                        <AlertDialogHeader>
                                                            <AlertDialogTitle>Confirmar exclusão</AlertDialogTitle>
                                                            <AlertDialogDescription>
                                                                Tem certeza que deseja excluir a campanha "{{ campaign.name }}"? Esta ação não pode ser desfeita.
                                                            </AlertDialogDescription>
                                                        </AlertDialogHeader>
                                                        <AlertDialogFooter>
                                                            <AlertDialogCancel>Cancelar</AlertDialogCancel>
                                                            <AlertDialogAction
                                                                @click="deleteCampaign(campaign.id)"
                                                                :class="buttonVariants({ variant: 'destructive' })"
                                                            >
                                                                Excluir
                                                            </AlertDialogAction>
                                                        </AlertDialogFooter>
                                                    </AlertDialogContent>
                                                </AlertDialog>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

