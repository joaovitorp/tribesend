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
import { useAnalytics } from '@/composables/useAnalytics';

interface FlashMessages {
    success?: string;
    error?: string;
}

const page = usePage();
const successMessage = computed(
    () => (page.props.flash as FlashMessages | undefined)?.success
);
const errorMessage = computed(
    () => (page.props.flash as FlashMessages | undefined)?.error
);
const { trackLead } = useAnalytics();

const form = useForm({
    email: '',
    name: '',
});

const submit = () => {
    form.post(waitlistSubscribe().url, {
        preserveScroll: true,
        onSuccess: () => {
            // Tracking de lead no GA4 (evento recomendado)
            trackLead({
                method: 'waitlist_landing_page',
                value: 1,
            });

            form.reset();
        },
    });
};
</script>

<template>
    <Head title="TribeSend - Create Amazing Newsletters in Minutes" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50 dark:from-slate-950 dark:via-blue-950 dark:to-purple-950">
        <!-- Header -->
        <header class="border-b border-slate-200/50 bg-white/80 backdrop-blur-sm dark:border-slate-800/50 dark:bg-slate-900/80">
            <div class="container mx-auto flex items-center justify-between px-4 py-4">
                <div class="flex items-center gap-2">
                    <Send class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">TribeSend</h1>
                </div>
                <nav class="hidden items-center gap-6 md:flex">
                    <a href="#benefits" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">Benefits</a>
                    <a href="#how-it-works" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">How It Works</a>
                    <a href="#cta" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">Get Started</a>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="container mx-auto px-4 py-20 md:py-32">
            <div class="mx-auto max-w-4xl text-center">
                <div class="mb-6 inline-block rounded-full bg-blue-100 px-4 py-2 text-sm font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                    ✨ Newsletter platform for content creators
                </div>
                
                <h1 class="mb-6 text-5xl font-bold leading-tight text-slate-900 dark:text-white md:text-6xl lg:text-7xl">
                    Create Amazing Newsletters in Minutes
                </h1>
                
                <p class="mb-10 text-xl text-slate-600 dark:text-slate-300 md:text-2xl">
                    TribeSend helps creators share content with their audience simply and effortlessly.
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
                            placeholder="Enter your email address"
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
                            {{ form.processing ? 'Sending...' : 'Get Early Access' }}
                            <Send class="h-5 w-5 transition-transform duration-300 group-hover:translate-x-1 group-hover:-translate-y-1" />
                        </span>
                        <div class="absolute inset-0 -z-0 bg-gradient-to-r from-blue-600 to-purple-600 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                    </Button>
                </form>

                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">
                    No credit card required. No commitment. Just your email.
                </p>
            </div>
        </section>

        <!-- Benefits Section -->
        <section id="benefits" class="bg-white/50 py-20 backdrop-blur-sm dark:bg-slate-900/50">
            <div class="container mx-auto px-4">
                <div class="mb-16 text-center">
                    <h2 class="mb-4 text-4xl font-bold text-slate-900 dark:text-white md:text-5xl">
                        Why Choose TribeSend?
                    </h2>
                    <p class="text-lg text-slate-600 dark:text-slate-300">
                        Everything you need to create and send professional newsletters
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                    <!-- Benefit 1 -->
                    <Card class="border-slate-200/50 transition-all hover:shadow-lg dark:border-slate-800/50">
                        <CardHeader>
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                <Zap class="h-6 w-6" />
                            </div>
                            <CardTitle class="text-xl">Easy-to-Use Editor</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <CardDescription class="text-base">
                                Create beautiful, professional newsletters in minutes without any coding knowledge.
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
                                AI Content Curation
                                <Sparkles class="h-4 w-4 text-violet-500 dark:text-violet-400" />
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="relative">
                            <CardDescription class="text-base">
                                Define keywords and stay updated with the best content. AI searches and organizes everything for you automatically.
                            </CardDescription>
                        </CardContent>
                    </Card>

                    <!-- Benefit 3 -->
                    <Card class="border-slate-200/50 transition-all hover:shadow-lg dark:border-slate-800/50">
                        <CardHeader>
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">
                                <Target class="h-6 w-6" />
                            </div>
                            <CardTitle class="text-xl">Smart Segmentation</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <CardDescription class="text-base">
                                Organize your audience by interests and send relevant content to each group.
                            </CardDescription>
                        </CardContent>
                    </Card>

                    <!-- Benefit 4 -->
                    <Card class="border-slate-200/50 transition-all hover:shadow-lg dark:border-slate-800/50">
                        <CardHeader>
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                <Mail class="h-6 w-6" />
                            </div>
                            <CardTitle class="text-xl">Reliable Deliverability</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <CardDescription class="text-base">
                                Your emails reach the inbox, not spam. Guaranteed.
                            </CardDescription>
                        </CardContent>
                    </Card>

                    <!-- Benefit 5 -->
                    <Card class="border-slate-200/50 transition-all hover:shadow-lg dark:border-slate-800/50">
                        <CardHeader>
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400">
                                <TrendingUp class="h-6 w-6" />
                            </div>
                            <CardTitle class="text-xl">Clear Analytics</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <CardDescription class="text-base">
                                Track opens, clicks, and engagement in real-time with intuitive dashboards.
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
                        How Does It Work?
                    </h2>
                    <p class="text-lg text-slate-600 dark:text-slate-300">
                        Three simple steps to start sending newsletters
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
                            Create Your Account
                        </h3>
                        <p class="text-slate-600 dark:text-slate-300">
                            Sign up for free in less than 1 minute. No hassle.
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
                            Import Your Subscribers
                        </h3>
                        <p class="text-slate-600 dark:text-slate-300">
                            Add your existing list or create custom capture forms.
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
                            Send Your First Newsletter
                        </h3>
                        <p class="text-slate-600 dark:text-slate-300">
                            Use our intuitive editor and send professional emails in minutes.
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
                        Be Among the First to Experience TribeSend
                    </h2>
                    
                    <p class="mb-10 text-xl text-blue-100">
                        Join the waitlist and get early access when we launch.
                    </p>

                    <!-- CTA Form -->
                    <form @submit.prevent="submit" class="mx-auto max-w-2xl">
                        <div class="flex flex-col gap-4 sm:flex-row">
                            <div class="flex-1">
                                <Input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="Enter your email address"
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
                                    {{ form.processing ? 'Sending...' : 'Join Waitlist' }}
                                    <Mail class="h-5 w-5 transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110" />
                                </span>
                                <div class="absolute inset-0 -z-0 bg-gradient-to-r from-slate-900 to-slate-700 opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
                            </Button>
                        </div>
                    </form>

                    <div class="mt-6 flex items-center justify-center gap-6 text-sm text-blue-100">
                        <div class="flex items-center gap-2">
                            <CheckCircle2 class="h-5 w-5" />
                            <span>Early access</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <CheckCircle2 class="h-5 w-5" />
                            <span>No upfront cost</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <CheckCircle2 class="h-5 w-5" />
                            <span>Priority support</span>
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
                            About
                        </a>
                        <a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                            Contact
                        </a>
                        <a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                            Privacy Policy
                        </a>
                    </nav>

                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        © 2025 TribeSend. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

