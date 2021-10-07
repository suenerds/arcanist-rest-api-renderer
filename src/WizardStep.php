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

    protected function rules(): array
    {
        $nested_rules = collect($this->fields())
            ->flatMap(function (Field $field) {
                return collect($field->nested_rules)
                    ->mapWithKeys(fn ($rule, $subField) => [
                        "$field->name.$subField" => $rule,
                    ]);
            })
            ->all();

        $field_rules = collect($this->fields())
            ->mapWithKeys(fn (Field $field) => [
                $field->name => $field->rules,
            ])
            ->all();

        return array_merge($nested_rules, $field_rules);
    }

    public function process(Request $request): StepResult
    {
        $data = $this->validate($request, $this->rules());

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
