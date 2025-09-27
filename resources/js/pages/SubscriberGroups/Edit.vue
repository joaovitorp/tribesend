<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Switch } from '@/components/ui/switch';
import type { BreadcrumbItem } from '@/types';

// Importar rotas necessárias
import { index as subscriberGroupsIndex, update as subscriberGroupsUpdate } from '@/routes/subscriber-groups';

interface SubscriberGroup {
    id: string;
    name: string;
    description: string | null;
    is_active: boolean;
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
    {
        title: 'Editar',
        href: '#',
    },
];

const form = useForm({
    name: props.subscriberGroup.name,
    description: props.subscriberGroup.description || '',
    is_active: Boolean(props.subscriberGroup.is_active),
});


const submit = () => {
    form.put(subscriberGroupsUpdate({ subscriber_group: props.subscriberGroup.id }).url, {
        onSuccess: () => {
            // Redirecionar para lista após sucesso é feito pelo controller
        },
    });
};
</script>

<template>
    <Head :title="`Editar ${subscriberGroup.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="max-w-2xl mx-auto w-full">
                <Card>
                    <CardHeader>
                        <CardTitle>Editar Grupo de Assinantes</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Nome -->
                            <div class="space-y-2">
                                <Label for="name">Nome do Grupo *</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    placeholder="Ex: Newsletter Semanal, Promoções, etc."
                                    :class="{ 'border-destructive': form.errors.name }"
                                />
                                <p v-if="form.errors.name" class="text-sm text-destructive">
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <!-- Descrição -->
                            <div class="space-y-2">
                                <Label for="description">Descrição</Label>
                                <Textarea
                                    id="description"
                                    v-model="form.description"
                                    placeholder="Descreva o propósito deste grupo..."
                                    rows="3"
                                    :class="{ 'border-destructive': form.errors.description }"
                                />
                                <p v-if="form.errors.description" class="text-sm text-destructive">
                                    {{ form.errors.description }}
                                </p>
                                <p class="text-sm text-muted-foreground">
                                    Opcional. Ajuda a identificar o propósito do grupo.
                                </p>
                            </div>

                            <!-- Status -->
                            <div class="flex items-center justify-between p-4 border rounded-lg">
                                <div class="space-y-1">
                                    <Label for="is_active">Grupo Ativo</Label>
                                    <p class="text-sm text-muted-foreground">
                                        Grupos ativos podem receber novos assinantes e campanhas.
                                    </p>
                                </div>
                                <Switch
                                    id="is_active"
                                    v-model="form.is_active"
                                />
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end gap-4 pt-4">
                                <Button 
                                    type="button" 
                                    variant="outline"
                                    @click="$inertia.visit(subscriberGroupsIndex().url)"
                                >
                                    Cancelar
                                </Button>
                                <Button type="submit" :disabled="form.processing">
                                    {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
