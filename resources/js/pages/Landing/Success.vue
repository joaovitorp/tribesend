<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { CheckCircle2, Mail, Send, Sparkles, ArrowLeft } from 'lucide-vue-next';
import { home } from '@/routes';

interface Props {
    email: string;
}

const props = defineProps<Props>();
</script>

<template>
    <Head title="Subscription Confirmed - TribeSend" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50 dark:from-slate-950 dark:via-blue-950 dark:to-purple-950">
        <!-- Header -->
        <header class="border-b border-slate-200/50 bg-white/80 backdrop-blur-sm dark:border-slate-800/50 dark:bg-slate-900/80">
            <div class="container mx-auto flex items-center justify-between px-4 py-4">
                <Link :href="home().url" class="flex items-center gap-2 transition-opacity hover:opacity-80">
                    <Send class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">TribeSend</h1>
                </Link>
            </div>
        </header>

        <!-- Success Section -->
        <section class="container mx-auto px-4 py-20 md:py-32">
            <div class="mx-auto max-w-3xl">
                <!-- Success Icon Animation -->
                <div class="mb-8 flex justify-center">
                    <div class="relative">
                        <div class="absolute inset-0 animate-ping rounded-full bg-green-400 opacity-75"></div>
                        <div class="relative flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-green-400 to-green-600 shadow-2xl">
                            <CheckCircle2 class="h-12 w-12 text-white" />
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="text-center">
                    <h1 class="mb-4 text-4xl font-bold text-slate-900 dark:text-white md:text-5xl lg:text-6xl">
                        🎉 Subscription Confirmed!
                    </h1>
                    
                    <p class="mb-8 text-xl text-slate-600 dark:text-slate-300 md:text-2xl">
                        Thank you for joining the TribeSend waitlist!
                    </p>
                </div>

                <!-- Email Confirmation Card -->
                <Card class="mb-8 border-green-200 bg-gradient-to-br from-green-50 to-emerald-50 dark:border-green-800 dark:from-green-950/30 dark:to-emerald-950/30">
                    <CardHeader>
                        <div class="flex items-center gap-3">
                            <Mail class="h-6 w-6 text-green-600 dark:text-green-400" />
                            <CardTitle class="text-green-900 dark:text-green-100">Registered Email</CardTitle>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <p class="text-lg font-semibold text-green-700 dark:text-green-300">
                            {{ email }}
                        </p>
                        <p class="mt-2 text-sm text-green-600 dark:text-green-400">
                            You'll receive updates and early access at this email address.
                        </p>
                    </CardContent>
                </Card>

                <!-- Next Steps -->
                <div class="mb-12">
                    <h2 class="mb-6 text-center text-2xl font-bold text-slate-900 dark:text-white">
                        What Happens Next?
                    </h2>

                    <div class="grid gap-6 md:grid-cols-3">
                        <!-- Step 1 -->
                        <Card class="border-slate-200/50 transition-all hover:shadow-lg dark:border-slate-800/50">
                            <CardHeader>
                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                    <span class="text-xl font-bold">1</span>
                                </div>
                                <CardTitle class="text-lg">Email Confirmation</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <CardDescription class="text-base">
                                    Check your inbox. We've sent you a welcome email.
                                </CardDescription>
                            </CardContent>
                        </Card>

                        <!-- Step 2 -->
                        <Card class="border-slate-200/50 transition-all hover:shadow-lg dark:border-slate-800/50">
                            <CardHeader>
                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">
                                    <span class="text-xl font-bold">2</span>
                                </div>
                                <CardTitle class="text-lg">Regular Updates</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <CardDescription class="text-base">
                                    You'll receive news about TribeSend's development and launch.
                                </CardDescription>
                            </CardContent>
                        </Card>

                        <!-- Step 3 -->
                        <Card class="border-slate-200/50 transition-all hover:shadow-lg dark:border-slate-800/50">
                            <CardHeader>
                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                    <span class="text-xl font-bold">3</span>
                                </div>
                                <CardTitle class="text-lg">Early Access</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <CardDescription class="text-base">
                                    You'll be among the first to test the platform when it's ready!
                                </CardDescription>
                            </CardContent>
                        </Card>
                    </div>
                </div>

                <!-- Benefits Reminder -->
                <Card class="mb-8 border-blue-200 bg-gradient-to-br from-blue-50 to-purple-50 dark:border-blue-800 dark:from-blue-950/30 dark:to-purple-950/30">
                    <CardHeader>
                        <div class="flex items-center gap-3">
                            <Sparkles class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                            <CardTitle class="text-blue-900 dark:text-blue-100">Exclusive Benefits</CardTitle>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3">
                                <CheckCircle2 class="mt-0.5 h-5 w-5 flex-shrink-0 text-blue-600 dark:text-blue-400" />
                                <span class="text-blue-800 dark:text-blue-200">
                                    <strong>Early access</strong> to the platform before official launch
                                </span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckCircle2 class="mt-0.5 h-5 w-5 flex-shrink-0 text-blue-600 dark:text-blue-400" />
                                <span class="text-blue-800 dark:text-blue-200">
                                    <strong>Special discount</strong> on subscription for early adopters
                                </span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckCircle2 class="mt-0.5 h-5 w-5 flex-shrink-0 text-blue-600 dark:text-blue-400" />
                                <span class="text-blue-800 dark:text-blue-200">
                                    <strong>Priority support</strong> from the TribeSend team
                                </span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckCircle2 class="mt-0.5 h-5 w-5 flex-shrink-0 text-blue-600 dark:text-blue-400" />
                                <span class="text-blue-800 dark:text-blue-200">
                                    <strong>Influence development</strong> with direct feedback to the team
                                </span>
                            </li>
                        </ul>
                    </CardContent>
                </Card>

                <!-- Social Share -->
                <div class="mb-12 text-center">
                    <p class="mb-4 text-lg text-slate-600 dark:text-slate-300">
                        Know someone who would love TribeSend too?
                    </p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Share the waitlist with your content creator friends!
                    </p>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <Button 
                        as-child
                        variant="outline"
                        size="lg"
                        class="group w-full transition-all duration-300 hover:scale-105 hover:shadow-lg sm:w-auto"
                    >
                        <Link :href="home().url" class="flex items-center gap-2">
                            <ArrowLeft class="h-5 w-5 transition-transform duration-300 group-hover:-translate-x-1" />
                            Back to Home
                        </Link>
                    </Button>

                    <Button 
                        as-child
                        size="lg"
                        class="group w-full bg-gradient-to-r from-blue-600 to-purple-600 transition-all duration-300 hover:scale-105 hover:shadow-xl sm:w-auto"
                    >
                        <a 
                            href="https://twitter.com/intent/tweet?text=I%20just%20joined%20the%20TribeSend%20waitlist!%20%F0%9F%9A%80%20A%20newsletter%20platform%20for%20content%20creators.%20%23TribeSend"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center gap-2"
                        >
                            Share on Twitter
                            <Send class="h-5 w-5 transition-transform duration-300 group-hover:translate-x-1 group-hover:-translate-y-1" />
                        </a>
                    </Button>
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

                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        © 2025 TribeSend. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

