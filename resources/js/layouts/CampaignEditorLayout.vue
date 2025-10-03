<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { index as campaignsIndex } from '@/routes/campaigns';
import { ArrowLeft, Check } from 'lucide-vue-next';

interface Step {
    number: number;
    title: string;
    description: string;
}

interface Props {
    title: string;
    campaign?: {
        id: string;
        name: string;
    };
    steps?: Step[];
    currentStep?: number;
}

const props = withDefaults(defineProps<Props>(), {
    currentStep: 1,
});
</script>

<template>
    <Head :title="title" />

    <div class="flex min-h-screen flex-col bg-background">
        <!-- Header -->
        <header class="sticky top-0 z-50 border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
            <div class="flex h-16 items-center justify-between gap-4 px-6">
                <!-- Left: Back button -->
                <div class="flex items-center shrink-0">
                    <Button variant="ghost" size="sm" as-child>
                        <Link :href="campaignsIndex().url">
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Voltar
                        </Link>
                    </Button>
                </div>

                <!-- Center: Steps Progress Bar -->
                <div v-if="steps && steps.length > 0" class="flex flex-1 items-center justify-center max-w-2xl mx-auto">
                    <div class="flex items-center justify-between w-full">
                        <div 
                            v-for="(step, index) in steps" 
                            :key="step.number"
                            class="flex flex-1 items-center"
                        >
                            <div class="flex items-center gap-2">
                                <div 
                                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border-2 text-xs font-semibold transition-colors"
                                    :class="[
                                        currentStep >= step.number 
                                            ? 'border-primary bg-primary text-primary-foreground' 
                                            : 'border-muted-foreground/30 bg-background text-muted-foreground'
                                    ]"
                                >
                                    <Check v-if="currentStep > step.number" class="h-3.5 w-3.5" />
                                    <span v-else>{{ step.number }}</span>
                                </div>
                                <div class="hidden lg:block">
                                    <p class="text-xs font-medium whitespace-nowrap" :class="currentStep >= step.number ? 'text-foreground' : 'text-muted-foreground'">
                                        {{ step.title }}
                                    </p>
                                </div>
                            </div>
                            
                            <div 
                                v-if="index < steps.length - 1"
                                class="mx-2 h-0.5 flex-1 transition-colors"
                                :class="currentStep > step.number ? 'bg-primary' : 'bg-muted-foreground/20'"
                            />
                        </div>
                    </div>
                </div>

                <!-- Center: Campaign name (when no steps) -->
                <div v-else-if="campaign" class="flex-1 text-center">
                    <p class="text-sm font-medium text-muted-foreground">
                        {{ campaign.name }}
                    </p>
                </div>

                <!-- Right: Actions slot -->
                <div class="flex items-center gap-2 shrink-0">
                    <slot name="actions" />
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Navigation slot in footer -->
        <div class="border-t bg-background">
            <div class="mx-auto max-w-5xl px-6 py-4">
                <slot name="navigation" />
            </div>
        </div>
    </div>
</template>
