<?php

namespace Suenerds\ArcanistRestApiRenderer;

use Arcanist\StepResult;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Arcanist\WizardStep as ArcanistStep;
use Suenerds\ArcanistRestApiRenderer\Fields\Field;

class WizardStep extends ArcanistStep
{
    public function viewData(Request $request): array
    {
        return collect($this->fields())->mapWithKeys(function (Field $field) {
            $key = explode('.', $field->name)[0];
            return [ $key => $field->display($this->data($key)) ];
        })->toArray();
    }
}
