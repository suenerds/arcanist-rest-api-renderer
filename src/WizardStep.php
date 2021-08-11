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

    public function process(Request $request): StepResult
    {
        // @TODO : we need this because rules() is private 
        $rules = collect($this->fields())
            ->mapWithKeys(fn (Field $field) => [$field->name => $field->rules])
            ->all();

        $data = $this->validate($request, $rules); // @TODO: rules() in ArcanistStep is _private_

        return collect($this->fields())
            ->filter(function (Field $field) {
                return $field->isEditable();
            })
            ->mapWithKeys(fn (Field $field) => [
                $field->name => $field->value($data[$field->name] ?? null)
            ])
            ->pipe(fn (Collection $values) => $this->handle($request, $values->toArray()));
    }
}
