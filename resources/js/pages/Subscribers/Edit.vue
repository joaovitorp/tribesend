<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import type { BreadcrumbItem } from '@/types';
// Importar rotas necessárias
import { dashboard } from '@/routes';
import { index as subscribersIndex, update as subscribersUpdate } from '@/routes/subscribers';

interface Subscriber {
    id: string;
    email: string;
    first_name: string | null;
    last_name: string | null;
    status: 'active' | 'inactive' | 'unsubscribed';
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
        title: 'Editar Assinante',
        href: '#',
    },
];

const form = useForm({
    email: props.subscriber.email,
    first_name: props.subscriber.first_name || '',
    last_name: props.subscriber.last_name || '',
    status: props.subscriber.status,
});

const submit = () => {
    form.put(subscribersUpdate({ subscriber: props.subscriber.id }).url, {
        onSuccess: () => {
            // Redirecionamento será feito pelo controller
        },
    });
};
</script>

<template>
    <Head title="Editar Assinante" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Editar Assinante</h1>
                    <p class="text-muted-foreground">
                        Atualize as informações do assinante
                    </p>
                </div>
            </div>

            <div class="max-w-2xl">
                <Card>
                    <CardHeader>
                        <CardTitle>Informações do Assinante</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Email -->
                            <div class="space-y-2">
                                <Label for="email">Email *</Label>
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    placeholder="email@exemplo.com"
                                    :class="{ 'border-destructive': form.errors.email }"
                                    required
                                />
                                <p v-if="form.errors.email" class="text-sm text-destructive">
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <!-- Nome -->
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="first_name">Primeiro Nome</Label>
                                    <Input
                                        id="first_name"
                                        v-model="form.first_name"
                                        placeholder="João"
                                        :class="{ 'border-destructive': form.errors.first_name }"
                                    />
                                    <p v-if="form.errors.first_name" class="text-sm text-destructive">
                                        {{ form.errors.first_name }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="last_name">Sobrenome</Label>
                                    <Input
                                        id="last_name"
                                        v-model="form.last_name"
                                        placeholder="Silva"
                                        :class="{ 'border-destructive': form.errors.last_name }"
                                    />
                                    <p v-if="form.errors.last_name" class="text-sm text-destructive">
                                        {{ form.errors.last_name }}
                                    </p>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <Label for="status">Status *</Label>
                                <Select v-model="form.status">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Selecione o status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="active">Ativo</SelectItem>
                                        <SelectItem value="inactive">Inativo</SelectItem>
                                        <SelectItem value="unsubscribed">Cancelado</SelectItem>
                                    </SelectContent>
                                </Select>
                                <p v-if="form.errors.status" class="text-sm text-destructive">
                                    {{ form.errors.status }}
                                </p>
                            </div>

                            <!-- Botões -->
                            <div class="flex items-center gap-4">
                                <Button type="submit" :disabled="form.processing">
                                    {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                                </Button>
                                <Button
                                    type="button"
                                    variant="outline"
                                    @click="$inertia.visit(subscribersIndex().url)"
                                >
                                    Cancelar
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>



