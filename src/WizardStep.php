<?php

namespace Suenerds\ArcanistRestApiRenderer;

use Arcanist\StepResult;
use Illuminate\Support\Arr;
use Arcanist\AbstractWizard;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Arcanist\WizardStep as ArcanistStep;
use Suenerds\ArcanistRestApiRenderer\Fields\Field;

class WizardStep extends ArcanistStep
{
    protected array $validator_messages = [];
    protected array $validator_attributes = [];
    protected AbstractWizard $wizard;

    public function init(AbstractWizard $wizard, int $index): self
    {
        parent::init($wizard, $index);
        $this->wizard = $wizard;

        return $this;
    }

    public function viewData(Request $request): array
    {
        return collect($this->fields())->mapWithKeys(function (Field $field) {
            $key = explode('.', $field->name)[0];

            return [$key => $field->display($this->data($key))];
        })->toArray();
    }

    protected function rules(): array
    {
        return collect($this->fields())->mapWithKeys(function (Field $field) {
            return Arr::isAssoc($field->rules)
                ? $field->rules
                : [$field->name => $field->rules];
        })->all();
    }

    public function process(Request $request): StepResult
    {
        $data = $this->validate(
            $request,
            $this->rules(),
            $this->validator_messages,
            $this->validator_attributes
        );

        return collect($this->fields())
            ->filter(function (Field $field) {
                return $field->isEditable();
            })
            ->mapWithKeys(fn (Field $field) => [
                $field->name => $field->value($data[$field->name] ?? null),
            ])
            ->pipe(fn (Collection $values) => $this->handle($request, $values->toArray()));
    }

    /**
     * @TODO find a better way to solve this issue:
     * this functionality loads all fields of all steps
     * this means for us a bunch of data gets loaded
     */
    public function dependentFields(): array
    {
        // quick fix: no dependsOn anymore :D
        return [];
    }
}
