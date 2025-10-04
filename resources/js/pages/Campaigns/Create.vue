<script setup lang="ts">
import CampaignEditorLayout from '@/layouts/CampaignEditorLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { Badge } from '@/components/ui/badge';
import TiptapEditor from '@/components/TiptapEditor.vue';
import ContentBlocksLibrary from '@/components/ContentBlocksLibrary.vue';
import { index as campaignsIndex, store as campaignsStore } from '@/routes/campaigns';
import { ref, computed } from 'vue';
import type { ContentBlock } from '@/types/content-blocks';

interface Segment {
    id: string;
    name: string;
    description: string | null;
}

interface Props {
    segments: Segment[];
}

const props = defineProps<Props>();

const currentStep = ref(1);
const totalSteps = 4;

const form = useForm({
    name: '',
    subject: '',
    body_html: '<p>Digite o conteúdo da sua campanha aqui...</p>',
    body_text: '',
    status: 'draft',
    scheduled_at: null,
    included_segments: [] as string[],
    excluded_segments: [] as string[],
});

const steps = [
    { number: 1, title: 'Detalhes', description: 'Nome e assunto' },
    { number: 2, title: 'Conteúdo', description: 'Editor de e-mail' },
    { number: 3, title: 'Segmentos', description: 'Públicos-alvo' },
    { number: 4, title: 'Revisar', description: 'Confirmar e criar' },
];

const canGoNext = computed(() => {
    if (currentStep.value === 1) {
        return form.name && form.subject;
    }
    
    if (currentStep.value === 2) {
        return form.body_html;
    }
    
    if (currentStep.value === 3) {
        return form.included_segments.length > 0;
    }
    
    return true;
});

const nextStep = () => {
    if (currentStep.value < totalSteps && canGoNext.value) {
        currentStep.value++;
    }
};

const previousStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const toggleIncludedSegment = (segmentId: string) => {
    const index = form.included_segments.indexOf(segmentId);
    
    if (index > -1) {
        form.included_segments.splice(index, 1);
    } else {
        form.included_segments.push(segmentId);
    }
};

const toggleExcludedSegment = (segmentId: string) => {
    const index = form.excluded_segments.indexOf(segmentId);
    
    if (index > -1) {
        form.excluded_segments.splice(index, 1);
    } else {
        form.excluded_segments.push(segmentId);
    }
};

const createCampaign = () => {
    console.log('Creating campaign with data:', form.data());
    
    form.post(campaignsStore().url, {
        onSuccess: () => {
            console.log('Campaign created successfully!');
        },
        onError: (errors) => {
            console.error('Campaign creation failed:', errors);
        },
    });
};

const cancelAndGoBack = () => {
    if (confirm('Deseja cancelar a criação da campanha? Todas as alterações serão perdidas.')) {
        router.visit(campaignsIndex().url);
    }
};

const handleBlockDragStart = (block: ContentBlock) => {
    console.log('Dragging block:', block.name);
};
</script>

<template>
    <CampaignEditorLayout 
        title="Nova Campanha" 
        :steps="steps"
        :current-step="currentStep"
    >
        <template #actions>
            <Button variant="ghost" @click="cancelAndGoBack">
                Cancelar
            </Button>
        </template>

        <div class="mx-auto max-w-5xl py-8 px-6">
            <!-- Step 1: Details -->
            <div v-if="currentStep === 1">
                <Card class="border-0 shadow-none">
                    <CardHeader class="px-0">
                        <CardTitle class="text-2xl">Detalhes da Campanha</CardTitle>
                        <CardDescription class="text-base">
                            Defina o nome interno e o assunto do e-mail
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6 px-0">
                        <div class="space-y-2">
                            <Label for="name" class="text-base">Nome da Campanha</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="Ex: Newsletter Semanal"
                                class="h-11 text-base"
                                :class="{ 'border-destructive': form.errors.name }"
                            />
                            <p v-if="form.errors.name" class="text-sm text-destructive">
                                {{ form.errors.name }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Este nome é apenas para uso interno
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="subject" class="text-base">Assunto do E-mail</Label>
                            <Input
                                id="subject"
                                v-model="form.subject"
                                placeholder="Ex: Novidades desta semana"
                                class="h-11 text-base"
                                :class="{ 'border-destructive': form.errors.subject }"
                            />
                            <p v-if="form.errors.subject" class="text-sm text-destructive">
                                {{ form.errors.subject }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Este será o assunto que os destinatários verão
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Step 2: Content -->
            <div v-if="currentStep === 2" class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-6">
                <!-- Biblioteca de Blocos -->
                <div class="order-2 lg:order-1">
                    <div class="sticky top-4">
                        <ContentBlocksLibrary @block-drag-start="handleBlockDragStart" />
                    </div>
                </div>

                <!-- Editor -->
                <div class="order-1 lg:order-2">
                    <Card class="border-0 shadow-none">
                        <CardHeader class="px-0">
                            <CardTitle class="text-2xl">Conteúdo da Campanha</CardTitle>
                            <CardDescription class="text-base">
                                Arraste blocos da biblioteca ou escreva seu próprio conteúdo
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4 px-0">
                            <div class="space-y-2">
                                <TiptapEditor v-model="form.body_html" />
                                <p v-if="form.errors.body_html" class="text-sm text-destructive">
                                    {{ form.errors.body_html }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Step 3: Segments -->
            <div v-if="currentStep === 3">
                <Card class="border-0 shadow-none">
                    <CardHeader class="px-0">
                        <CardTitle class="text-2xl">Segmentos de Público</CardTitle>
                        <CardDescription class="text-base">
                            Selecione os segmentos que receberão esta campanha
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-8 px-0">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <Label class="text-base">Incluir Segmentos</Label>
                                    <p class="mt-1 text-sm text-muted-foreground">
                                        Assinantes destes segmentos receberão a campanha
                                    </p>
                                </div>
                                <Badge variant="secondary">{{ form.included_segments.length }} selecionados</Badge>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div
                                    v-for="segment in segments"
                                    :key="segment.id"
                                    class="flex items-start space-x-3 rounded-lg border p-4 transition-colors hover:bg-muted/50 cursor-pointer"
                                    @click="toggleIncludedSegment(segment.id)"
                                >
                                    <Checkbox
                                        :id="`include-${segment.id}`"
                                        :checked="form.included_segments.includes(segment.id)"
                                        @click.stop
                                    />
                                    <div class="flex-1">
                                        <label
                                            :for="`include-${segment.id}`"
                                            class="cursor-pointer text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                        >
                                            {{ segment.name }}
                                        </label>
                                        <p v-if="segment.description" class="mt-1 text-sm text-muted-foreground">
                                            {{ segment.description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <p v-if="form.errors.included_segments" class="text-sm text-destructive">
                                {{ form.errors.included_segments }}
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <Label class="text-base">Excluir Segmentos (opcional)</Label>
                                    <p class="mt-1 text-sm text-muted-foreground">
                                        Assinantes destes segmentos NÃO receberão a campanha
                                    </p>
                                </div>
                                <Badge variant="secondary">{{ form.excluded_segments.length }} selecionados</Badge>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div
                                    v-for="segment in segments"
                                    :key="segment.id"
                                    class="flex items-start space-x-3 rounded-lg border p-4 transition-colors hover:bg-muted/50 cursor-pointer"
                                    @click="toggleExcludedSegment(segment.id)"
                                >
                                    <Checkbox
                                        :id="`exclude-${segment.id}`"
                                        :checked="form.excluded_segments.includes(segment.id)"
                                        @click.stop
                                    />
                                    <div class="flex-1">
                                        <label
                                            :for="`exclude-${segment.id}`"
                                            class="cursor-pointer text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                        >
                                            {{ segment.name }}
                                        </label>
                                        <p v-if="segment.description" class="mt-1 text-sm text-muted-foreground">
                                            {{ segment.description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Step 4: Review -->
            <div v-if="currentStep === 4">
                <Card class="border-0 shadow-none">
                    <CardHeader class="px-0">
                        <CardTitle class="text-2xl">Revisar e Criar</CardTitle>
                        <CardDescription class="text-base">
                            Confira todos os detalhes antes de criar a campanha
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6 px-0">
                        <div class="space-y-4 rounded-lg border p-6">
                            <h3 class="font-semibold text-lg">Detalhes</h3>
                            <dl class="grid gap-3">
                                <div class="flex justify-between">
                                    <dt class="text-muted-foreground">Nome:</dt>
                                    <dd class="font-medium">{{ form.name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-muted-foreground">Assunto:</dt>
                                    <dd class="font-medium">{{ form.subject }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="space-y-4 rounded-lg border p-6">
                            <h3 class="font-semibold text-lg">Segmentos</h3>
                            <dl class="grid gap-3">
                                <div class="flex justify-between">
                                    <dt class="text-muted-foreground">Incluídos:</dt>
                                    <dd class="font-medium">{{ form.included_segments.length }} segmentos</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-muted-foreground">Excluídos:</dt>
                                    <dd class="font-medium">{{ form.excluded_segments.length }} segmentos</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="rounded-lg border border-blue-500/50 bg-blue-500/10 p-4">
                            <p class="text-sm font-medium">
                                A campanha será criada como rascunho. Você poderá editá-la e enviá-la mais tarde.
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>

        <!-- Navigation -->
        <template #navigation>
            <div class="flex justify-between">
                <Button
                    variant="outline"
                    size="lg"
                    @click="previousStep"
                    :disabled="currentStep === 1"
                >
                    Anterior
                </Button>

                <Button
                    v-if="currentStep < totalSteps"
                    size="lg"
                    @click="nextStep"
                    :disabled="!canGoNext || form.processing"
                >
                    Próximo
                </Button>

                <Button
                    v-else
                    size="lg"
                    @click="createCampaign"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Criando...' : 'Criar Campanha' }}
                </Button>
            </div>
        </template>
    </CampaignEditorLayout>
</template>
