<?php

namespace Suenerds\ArcanistRestApiRenderer;

use Arcanist\WizardStep;
use Arcanist\AbstractWizard;
use Illuminate\Http\JsonResponse;
use Arcanist\Contracts\ResponseRenderer;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseRenderer implements ResponseRenderer
{
    public function renderStep(WizardStep $step, AbstractWizard $wizard, array $data = []): Response | Responsable | Renderable
    {
        return new JsonResponse([
            'wizard' => $wizard->summary(),
            'step' => $step,
            'fields' => $step->fields(),
            'formData' => $data,
        ]);
    }

    public function redirect(WizardStep $step, AbstractWizard $wizard): Response | Responsable | Renderable
    {
        return $this->jsonRedirect('step', [
            'wizardSlug' => $wizard::$slug,
            'wizardId' => $wizard->getId(),
            'step' => $step->slug,
        ]);
    }

    public function redirectWithError(WizardStep $step, AbstractWizard $wizard, ?string $error = null): Response | Responsable | Renderable
    {
        return $this->jsonRedirect(
            name: 'step',
            params: [
                'wizardSlug' => $wizard::$slug,
                'wizardId' => $wizard->getId(),
                'step' => $step->slug,
            ],
            error: $error
        );
    }

    public function jsonRedirect(string $name, array $params, ?string $error = null) : Response | Responsable | Renderable
    {
        return new JsonResponse([
            'redirect' => [
                'name' => $name,
                'params' => $params
            ],
            'error' => $error,
        ]);
    }
}
