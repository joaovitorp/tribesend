<script setup lang="ts">
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import onboarding from '@/routes/onboarding';

interface Props {
    categories: string[];
}

const props = defineProps<Props>();

const form = useForm({
    name: '',
    description: '',
    category: '',
});

const submit = () => {
    form.post(onboarding.team.store.url(), {
        onSuccess: () => {
            // Redirecionamento será feito pelo controller
        },
    });
};

// Mapeamento de categorias para ícones (usando emojis simples)
const categoryIcons: Record<string, string> = {
    'AI': '🤖',
    'Developer': '💻',
    'Marketing': '📈',
    'Game Development': '🎮',
    'Journalist': '📰',
    'Writer': '✍️',
    'Travel': '✈️',
    'E-commerce': '🛒',
    'Finance': '💰',
    'Healthcare': '⚕️',
    'Education': '📚',
    'Consulting': '💼',
    'Design': '🎨',
    'Photography': '📸',
    'Music': '🎵',
    'Sports': '⚽',
    'Food & Beverage': '🍽️',
    'Real Estate': '🏠',
    'Legal': '⚖️',
    'Non-profit': '🤝',
    'Other': '📋',
};
</script>

<template>
    <Head title="Criar seu Time - Onboarding" />

    <AuthLayout 
        title="Bem-vindo ao TribeSend!" 
        description="Vamos começar criando seu primeiro time. Isso nos ajudará a personalizar sua experiência."
    >
        <Card>
            <CardHeader class="text-center">
                <CardTitle class="text-lg">Crie seu Time</CardTitle>
                <p class="text-sm text-muted-foreground">
                    Configure as informações básicas do seu time
                </p>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Nome do Time -->
                    <div class="space-y-2">
                        <Label for="name" class="text-sm font-medium">
                            Nome do Time *
                        </Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Ex: Minha Startup, Agência Digital..."
                            :class="{ 'border-destructive': form.errors.name }"
                            required
                        />
                        <p v-if="form.errors.name" class="text-sm text-destructive">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Categoria -->
                    <div class="space-y-2">
                        <Label for="category" class="text-sm font-medium">
                            Categoria *
                        </Label>
                        <Select v-model="form.category" required>
                            <SelectTrigger 
                                id="category"
                                :class="{ 'border-destructive': form.errors.category }"
                            >
                                <SelectValue placeholder="Selecione a categoria do seu time" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem 
                                    v-for="category in categories" 
                                    :key="category" 
                                    :value="category"
                                >
                                    <div class="flex items-center gap-2">
                                        <span>{{ categoryIcons[category] || '📋' }}</span>
                                        <span>{{ category }}</span>
                                    </div>
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <p v-if="form.errors.category" class="text-sm text-destructive">
                            {{ form.errors.category }}
                        </p>
                        <p class="text-xs text-muted-foreground">
                            Isso nos ajuda a personalizar sua experiência
                        </p>
                    </div>

                    <!-- Descrição -->
                    <div class="space-y-2">
                        <Label for="description" class="text-sm font-medium">
                            Descrição (Opcional)
                        </Label>
                        <Textarea
                            id="description"
                            v-model="form.description"
                            placeholder="Conte-nos um pouco sobre seu time e seus objetivos..."
                            rows="3"
                            :class="{ 'border-destructive': form.errors.description }"
                        />
                        <p v-if="form.errors.description" class="text-sm text-destructive">
                            {{ form.errors.description }}
                        </p>
                        <p class="text-xs text-muted-foreground">
                            Máximo de 1000 caracteres
                        </p>
                    </div>

                    <!-- Botão de Submit -->
                    <Button 
                        type="submit" 
                        :disabled="form.processing || !form.name || !form.category"
                        class="w-full"
                        size="lg"
                    >
                        <div v-if="form.processing" class="flex items-center gap-2">
                            <div class="h-4 w-4 animate-spin rounded-full border-2 border-background border-t-transparent"></div>
                            <span>Criando time...</span>
                        </div>
                        <span v-else>Criar Time e Continuar</span>
                    </Button>

                    <!-- Informação adicional -->
                    <div class="text-center">
                        <p class="text-xs text-muted-foreground">
                            Você poderá editar essas informações posteriormente nas configurações
                        </p>
                    </div>
                </form>
            </CardContent>
        </Card>
    </AuthLayout>
</template>
