<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { CheckCircle } from 'lucide-vue-next';
// Importar rotas necessárias
import { show as publicFormsShow } from '@/routes/public/forms';

interface Form {
    name: string;
    hash: string;
}

interface Props {
    form: Form;
}

const props = defineProps<Props>();
</script>

<template>
    <Head title="Inscrição Realizada" />

    <div class="min-h-screen bg-background flex items-center justify-center p-4">
        <Card class="w-full max-w-md text-center">
            <CardHeader>
                <div class="flex justify-center mb-4">
                    <CheckCircle class="h-16 w-16 text-green-500" />
                </div>
                <CardTitle class="text-2xl text-green-700">Inscrição Realizada!</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <p class="text-muted-foreground">
                    Sua inscrição em <strong>{{ form.name }}</strong> foi realizada com sucesso.
                </p>
                
                <p class="text-sm text-muted-foreground">
                    Você receberá um email de confirmação em breve.
                </p>

                <div class="pt-4">
                    <Button variant="outline" as-child>
                        <Link :href="publicFormsShow({ hash: form.hash }).url">
                            Voltar ao Formulário
                        </Link>
                    </Button>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
