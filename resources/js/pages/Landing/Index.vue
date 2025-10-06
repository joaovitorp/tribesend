<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { CheckCircle2, Mail, Users, TrendingUp, Zap, Target, Send, Sparkles, Bot, ArrowLeft } from 'lucide-vue-next';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { subscribe as waitlistSubscribe } from '@/routes/waitlist';

const page = usePage();
const successMessage = computed(() => page.props.flash?.success as string | undefined);
const errorMessage = computed(() => page.props.flash?.error as string | undefined);

const form = useForm({
    email: '',
    name: '',
});

const submit = () => {
    form.post(waitlistSubscribe().url, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="TribeSend - Crie newsletters incríveis em minutos" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50 dark:from-slate-950 dark:via-blue-950 dark:to-purple-950">
        <!-- Header -->
        <header class="border-b border-slate-200/50 bg-white/80 backdrop-blur-sm dark:border-slate-800/50 dark:bg-slate-900/80">
            <div class="container mx-auto flex items-center justify-between px-4 py-4">
                <div class="flex items-center gap-2">
                    <Send class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">TribeSend</h1>
                </div>
                <nav class="hidden items-center gap-6 md:flex">
                    <a href="#benefits" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">Benefícios</a>
                    <a href="#how-it-works" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">Como Funciona</a>
                    <a href="#cta" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">Começar</a>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="container mx-auto px-4 py-20 md:py-32">
            <div class="mx-auto max-w-4xl text-center">
                <div class="mb-6 inline-block rounded-full bg-blue-100 px-4 py-2 text-sm font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                    ✨ Plataforma para criadores de conteúdo
                </div>
                
                <h1 class="mb-6 text-5xl font-bold leading-tight text-slate-900 dark:text-white md:text-6xl lg:text-7xl">
                    Crie newsletters incríveis em minutos
                </h1>
                
                <p class="mb-10 text-xl text-slate-600 dark:text-slate-300 md:text-2xl">
                    TribeSend ajuda criadores a compartilhar conteúdo com sua audiência de forma simples e sem complicação.
                </p>

                <!-- Success/Error Messages -->
                <div v-if="successMessage" class="mx-auto mb-8 max-w-2xl">
                    <Alert class="border-green-200 bg-green-50 text-green-900 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300">
                        <CheckCircle2 class="h-4 w-4" />
                        <AlertDescription class="ml-2">
                            {{ successMessage }}
                        </AlertDescription>
                    </Alert>
                </div>

                <div v-if="errorMessage" class="mx-auto mb-8 max-w-2xl">
                    <Alert variant="destructive">
                        <AlertDescription>
                            {{ errorMessage }}
                        </AlertDescription>
                    </Alert>
                </div>

                <!-- Hero CTA Form -->
                <form @submit.prevent="submit" class="mx-auto flex max-w-2xl flex-col gap-4 sm:flex-row">
                    <div class="flex-1">
                        <Input
                            v-model="form.email"
                            type="email"
                            placeholder="Seu melhor email"
                            class="h-14 text-lg"
                            :class="{ 'border-destructive': form.errors.email }"
                        />
                        <p v-if="form.errors.email" class="mt-2 text-left text-sm text-destructive">
                            {{ form.errors.email }}
                        </p>
                    </div>
                    <Button 
                        type="submit" 
                        size="lg" 
                        class="group relative h-14 overflow-hidden px-8 text-lg font-semibold transition-all duration-300 hover:scale-105 hover:shadow-2xl"
                        :disabled="form.processing"
                    >
                        <span class="relative z-10 flex items-center gap-2">
                            {{ form.processing ? 'Enviando...' : 'Quero testar o TribeSend' }}
                            <Send class="h-5 w-5 transition-transform duration-300 group-hover:translate-x-1 group-hover:-translate-y-1" />
                        </span>
                        <div class="absolute inset-0 -z-0 bg-gradient-to-r from-blue-600 to-purple-600 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                    </Button>
                </form>

                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">
                    Sem cartão de crédito. Sem compromisso. Apenas seu email.
                </p>
            </div>
        </section>

        <!-- Benefits Section -->
        <section id="benefits" class="bg-white/50 py-20 backdrop-blur-sm dark:bg-slate-900/50">
            <div class="container mx-auto px-4">
                <div class="mb-16 text-center">
                    <h2 class="mb-4 text-4xl font-bold text-slate-900 dark:text-white md:text-5xl">
                        Por que usar o TribeSend?
                    </h2>
                    <p class="text-lg text-slate-600 dark:text-slate-300">
                        Tudo que você precisa para criar e enviar newsletters profissionais
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                    <!-- Benefit 1 -->
                    <Card class="border-slate-200/50 transition-all hover:shadow-lg dark:border-slate-800/50">
                        <CardHeader>
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                <Zap class="h-6 w-6" />
                            </div>
                            <CardTitle class="text-xl">Editor fácil de usar</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <CardDescription class="text-base">
                                Crie newsletters bonitas e profissionais em minutos, sem precisar saber código.
                            </CardDescription>
                        </CardContent>
                    </Card>

                    <!-- Benefit 2 - NEW: AI Content Curation -->
                    <Card class="group relative overflow-hidden border-slate-200/50 transition-all hover:scale-105 hover:shadow-2xl dark:border-slate-800/50">
                        <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 via-fuchsia-500/10 to-pink-500/10 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <CardHeader class="relative">
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-violet-100 to-fuchsia-100 text-violet-600 dark:from-violet-900/30 dark:to-fuchsia-900/30 dark:text-violet-400">
                                <Bot class="h-6 w-6 transition-transform duration-300 group-hover:rotate-12" />
                            </div>
                            <CardTitle class="flex items-center gap-2 text-xl">
                                Curadoria AI
                                <Sparkles class="h-4 w-4 text-violet-500 dark:text-violet-400" />
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="relative">
                            <CardDescription class="text-base">
                                Defina palavras-chave e fique por dentro dos melhores conteúdos. IA busca e organiza tudo para você automaticamente.
                            </CardDescription>
                        </CardContent>
                    </Card>

                    <!-- Benefit 3 -->
                    <Card class="border-slate-200/50 transition-all hover:shadow-lg dark:border-slate-800/50">
                        <CardHeader>
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">
                                <Target class="h-6 w-6" />
                            </div>
                            <CardTitle class="text-xl">Segmentação inteligente</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <CardDescription class="text-base">
                                Organize sua audiência por interesses e envie conteúdo relevante para cada grupo.
                            </CardDescription>
                        </CardContent>
                    </Card>

                    <!-- Benefit 4 -->
                    <Card class="border-slate-200/50 transition-all hover:shadow-lg dark:border-slate-800/50">
                        <CardHeader>
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                <Mail class="h-6 w-6" />
                            </div>
                            <CardTitle class="text-xl">Entregabilidade confiável</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <CardDescription class="text-base">
                                Seus emails chegam na caixa de entrada, não no spam. Garantido.
                            </CardDescription>
                        </CardContent>
                    </Card>

                    <!-- Benefit 5 -->
                    <Card class="border-slate-200/50 transition-all hover:shadow-lg dark:border-slate-800/50">
                        <CardHeader>
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400">
                                <TrendingUp class="h-6 w-6" />
                            </div>
                            <CardTitle class="text-xl">Estatísticas claras</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <CardDescription class="text-base">
                                Acompanhe aberturas, cliques e engajamento em tempo real com dashboards intuitivos.
                            </CardDescription>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-20">
            <div class="container mx-auto px-4">
                <div class="mb-16 text-center">
                    <h2 class="mb-4 text-4xl font-bold text-slate-900 dark:text-white md:text-5xl">
                        Como funciona?
                    </h2>
                    <p class="text-lg text-slate-600 dark:text-slate-300">
                        Três passos simples para começar a enviar newsletters
                    </p>
                </div>

                <div class="mx-auto grid max-w-4xl gap-12 md:grid-cols-3">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="mb-6 flex justify-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-600 text-2xl font-bold text-white dark:bg-blue-500">
                                1
                            </div>
                        </div>
                        <h3 class="mb-3 text-xl font-semibold text-slate-900 dark:text-white">
                            Crie sua conta
                        </h3>
                        <p class="text-slate-600 dark:text-slate-300">
                            Cadastre-se gratuitamente em menos de 1 minuto. Sem burocracia.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="mb-6 flex justify-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-purple-600 text-2xl font-bold text-white dark:bg-purple-500">
                                2
                            </div>
                        </div>
                        <h3 class="mb-3 text-xl font-semibold text-slate-900 dark:text-white">
                            Importe seus assinantes
                        </h3>
                        <p class="text-slate-600 dark:text-slate-300">
                            Adicione sua lista existente ou crie formulários de captura personalizados.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="mb-6 flex justify-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-600 text-2xl font-bold text-white dark:bg-green-500">
                                3
                            </div>
                        </div>
                        <h3 class="mb-3 text-xl font-semibold text-slate-900 dark:text-white">
                            Envie sua primeira newsletter
                        </h3>
                        <p class="text-slate-600 dark:text-slate-300">
                            Use nosso editor intuitivo e envie emails profissionais em minutos.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="cta" class="bg-gradient-to-r from-blue-600 to-purple-600 py-20 dark:from-blue-700 dark:to-purple-700">
            <div class="container mx-auto px-4 text-center">
                <div class="mx-auto max-w-3xl">
                    <Users class="mx-auto mb-6 h-16 w-16 text-white" />
                    
                    <h2 class="mb-6 text-4xl font-bold text-white md:text-5xl">
                        Seja um dos primeiros a experimentar o TribeSend
                    </h2>
                    
                    <p class="mb-10 text-xl text-blue-100">
                        Junte-se à lista de espera e receba acesso antecipado quando lançarmos.
                    </p>

                    <!-- CTA Form -->
                    <form @submit.prevent="submit" class="mx-auto max-w-2xl">
                        <div class="flex flex-col gap-4 sm:flex-row">
                            <div class="flex-1">
                                <Input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="Seu melhor email"
                                    class="h-14 border-white/20 bg-white/10 text-lg text-white placeholder:text-white/60 focus:bg-white focus:text-slate-900"
                                    :class="{ 'border-red-300': form.errors.email }"
                                />
                                <p v-if="form.errors.email" class="mt-2 text-left text-sm text-red-200">
                                    {{ form.errors.email }}
                                </p>
                            </div>
                            <Button 
                                type="submit" 
                                size="lg" 
                                variant="secondary"
                                class="group relative h-14 overflow-hidden px-8 text-lg font-semibold transition-all duration-300 hover:scale-105 hover:shadow-2xl"
                                :disabled="form.processing"
                            >
                                <span class="relative z-10 flex items-center gap-2">
                                    {{ form.processing ? 'Enviando...' : 'Entrar na lista de espera' }}
                                    <Mail class="h-5 w-5 transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110" />
                                </span>
                                <div class="absolute inset-0 -z-0 bg-gradient-to-r from-slate-900 to-slate-700 opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
                            </Button>
                        </div>
                    </form>

                    <div class="mt-6 flex items-center justify-center gap-6 text-sm text-blue-100">
                        <div class="flex items-center gap-2">
                            <CheckCircle2 class="h-5 w-5" />
                            <span>Acesso antecipado</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <CheckCircle2 class="h-5 w-5" />
                            <span>Sem custo inicial</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <CheckCircle2 class="h-5 w-5" />
                            <span>Suporte prioritário</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white py-12 dark:border-slate-800 dark:bg-slate-900">
            <div class="container mx-auto px-4">
                <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                    <div class="flex items-center gap-2">
                        <Send class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        <span class="text-lg font-bold text-slate-900 dark:text-white">TribeSend</span>
                    </div>

                    <nav class="flex flex-wrap justify-center gap-6">
                        <a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                            Sobre
                        </a>
                        <a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                            Contato
                        </a>
                        <a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                            Política de Privacidade
                        </a>
                    </nav>

                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        © 2025 TribeSend. Todos os direitos reservados.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

