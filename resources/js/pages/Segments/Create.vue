<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Switch } from '@/components/ui/switch';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { index as segmentsIndex, store as segmentsStore } from '@/routes/segments';

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
        title: 'Criar Segmento',
        href: '',
    },
];

const form = useForm({
    name: '',
    description: '',
    is_active: true,
});

const submit = () => {
    form.post(segmentsStore().url, {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Criar Segmento" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div>
                <h1 class="text-2xl font-semibold">Criar Segmento</h1>
                <p class="text-sm text-muted-foreground">
                    Crie um novo segmento de assinantes
                </p>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Informações do Segmento</CardTitle>
                    <CardDescription>Preencha os dados do segmento</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name">Nome *</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="Nome do segmento"
                                :class="{ 'border-destructive': form.errors.name }"
                            />
                            <p v-if="form.errors.name" class="text-sm text-destructive">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="description">Descrição</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                placeholder="Descrição do segmento"
                                :class="{ 'border-destructive': form.errors.description }"
                                rows="4"
                            />
                            <p v-if="form.errors.description" class="text-sm text-destructive">
                                {{ form.errors.description }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-2">
                            <Switch
                                id="is_active"
                                v-model:checked="form.is_active"
                            />
                            <Label for="is_active">Segmento ativo</Label>
                        </div>

                        <div class="flex justify-end gap-4">
                            <Button type="button" variant="outline" @click="$inertia.visit(segmentsIndex().url)">
                                Cancelar
                            </Button>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Criando...' : 'Criar Segmento' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

