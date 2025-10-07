<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
// Importar rotas necessárias
import { subscribe as publicFormsSubscribe } from '@/routes/public/forms';
import { useAnalytics } from '@/composables/useAnalytics';

interface FormField {
    name: string;
    type: string;
    label: string;
    required: boolean;
    options?: string[];
}

interface Form {
    id: string;
    name: string;
    content?: string;
    fields: FormField[];
    hash: string;
}

interface Props {
    form: Form;
}

const props = defineProps<Props>();
const { trackLead } = useAnalytics();

// Criar objeto inicial do formulário baseado nos campos
const initialData: Record<string, any> = {};

props.form.fields.forEach(field => {
    if (field.type === 'checkbox') {
        initialData[field.name] = false;
    } else {
        initialData[field.name] = '';
    }
});

const form = useForm(initialData);

const submit = () => {
    form.post(publicFormsSubscribe({ hash: props.form.hash }).url, {
        onSuccess: () => {
            // Tracking de lead no GA4 (evento recomendado)
            trackLead({
                method: `public_form_${props.form.hash}`,
                value: 1,
            });

            // Redirecionamento será feito pelo controller
        },
    });
};

const renderField = (field: FormField) => {
    switch (field.type) {
        case 'textarea':
            return 'textarea';
        case 'select':
            return 'select';
        case 'checkbox':
            return 'checkbox';
        case 'radio':
            return 'radio';
        default:
            return 'input';
    }
};

const getInputType = (fieldType: string) => {
    switch (fieldType) {
        case 'email':
            return 'email';
        case 'phone':
            return 'tel';
        default:
            return 'text';
    }
};
</script>

<template>
    <Head :title="props.form.name" />

    <div class="min-h-screen bg-background flex items-center justify-center p-4">
        <Card class="w-full max-w-2xl">
            <CardHeader class="text-center">
                <CardTitle class="text-2xl">{{ form.name }}</CardTitle>
                <p v-if="props.form.content" class="text-muted-foreground whitespace-pre-wrap">
                    {{ props.form.content }}
                </p>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit" class="space-y-6">
                    <div v-for="field in props.form.fields" :key="field.name" class="space-y-2">
                        <Label :for="field.name" class="flex items-center gap-1">
                            {{ field.label }}
                            <span v-if="field.required" class="text-destructive">*</span>
                        </Label>

                        <!-- Input padrão -->
                        <Input
                            v-if="renderField(field) === 'input'"
                            :id="field.name"
                            :type="getInputType(field.type)"
                            v-model="form[field.name]"
                            :class="{ 'border-destructive': form.errors[field.name] }"
                            :placeholder="field.label"
                        />

                        <!-- Textarea -->
                        <Textarea
                            v-else-if="renderField(field) === 'textarea'"
                            :id="field.name"
                            v-model="form[field.name]"
                            :class="{ 'border-destructive': form.errors[field.name] }"
                            :placeholder="field.label"
                            rows="4"
                        />

                        <!-- Select -->
                        <Select
                            v-else-if="renderField(field) === 'select'"
                            v-model="form[field.name]"
                        >
                            <SelectTrigger :class="{ 'border-destructive': form.errors[field.name] }">
                                <SelectValue :placeholder="`Selecione ${field.label.toLowerCase()}`" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="option in field.options" :key="option" :value="option">
                                    {{ option }}
                                </SelectItem>
                            </SelectContent>
                        </Select>

                        <!-- Checkbox -->
                        <div v-else-if="renderField(field) === 'checkbox'" class="flex items-center space-x-2">
                            <Checkbox
                                :id="field.name"
                                :checked="form[field.name]"
                                @update:checked="form[field.name] = $event"
                            />
                            <Label :for="field.name" class="text-sm font-normal">
                                {{ field.label }}
                            </Label>
                        </div>

                        <!-- Radio -->
                        <RadioGroup
                            v-else-if="renderField(field) === 'radio'"
                            v-model="form[field.name]"
                            class="space-y-2"
                        >
                            <div v-for="option in field.options" :key="option" class="flex items-center space-x-2">
                                <RadioGroupItem :id="`${field.name}-${option}`" :value="option" />
                                <Label :for="`${field.name}-${option}`" class="text-sm font-normal">
                                    {{ option }}
                                </Label>
                            </div>
                        </RadioGroup>

                        <p v-if="form.errors[field.name]" class="text-sm text-destructive">
                            {{ form.errors[field.name] }}
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <Button type="submit" class="w-full" :disabled="form.processing">
                        {{ form.processing ? 'Enviando...' : 'Inscrever-se' }}
                    </Button>

                    <!-- Error Messages -->
                    <div v-if="form.errors.form" class="text-sm text-destructive text-center">
                        {{ form.errors.form }}
                    </div>
                    <div v-if="form.errors.email && form.errors.email.includes('já está inscrito')" class="text-sm text-destructive text-center">
                        {{ form.errors.email }}
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
