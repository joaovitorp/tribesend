<?php

namespace App\Http\Controllers;

use App\Http\Requests\Form\SubscribeFormRequest;
use App\Models\Form;
use App\Services\Form\ProcessFormSubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PublicFormController extends Controller
{
    public function __construct(
        private ProcessFormSubscriptionService $processFormSubscriptionService
    ) {}

    /**
     * Display the public form.
     */
    public function show(string $hash): Response
    {
        $form = Form::byHash($hash)->valid()->firstOrFail();

        return Inertia::render('Public/Form', [
            'form' => [
                'id' => $form->id,
                'name' => $form->name,
                'content' => $form->content,
                'fields' => $form->fields,
                'hash' => $form->hash,
            ],
        ]);
    }

    /**
     * Process the form subscription.
     */
    public function subscribe(SubscribeFormRequest $request, string $hash): RedirectResponse
    {
        $form = Form::byHash($hash)->valid()->firstOrFail();

        try {
            // Validar dinamicamente baseado nos campos do formulário
            $rules = $this->buildValidationRules($form);
            $request->validate($rules);

            $subscriber = $this->processFormSubscriptionService->execute(
                $form,
                $request->all()
            );

            return redirect()
                ->route('forms.success', $hash)
                ->with('success', 'Inscrição realizada com sucesso!');

        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }

    /**
     * Show success page.
     */
    public function success(string $hash): Response
    {
        $form = Form::byHash($hash)->firstOrFail();

        return Inertia::render('Public/Success', [
            'form' => [
                'name' => $form->name,
                'hash' => $form->hash,
            ],
        ]);
    }

    /**
     * Build validation rules based on form fields.
     */
    private function buildValidationRules(Form $form): array
    {
        $rules = [
            'email' => ['required', 'email'],
        ];

        foreach ($form->fields as $field) {
            $fieldRules = [];

            if ($field['required'] ?? false) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            switch ($field['type']) {
                case 'email':
                    $fieldRules[] = 'email';
                    break;
                case 'phone':
                    $fieldRules[] = 'string';
                    break;
                case 'text':
                case 'textarea':
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:1000';
                    break;
                case 'select':
                case 'radio':
                    if (isset($field['options']) && is_array($field['options'])) {
                        $fieldRules[] = 'in:'.implode(',', $field['options']);
                    }
                    break;
                case 'checkbox':
                    $fieldRules[] = 'boolean';
                    break;
            }

            $rules[$field['name']] = $fieldRules;
        }

        return $rules;
    }
}
