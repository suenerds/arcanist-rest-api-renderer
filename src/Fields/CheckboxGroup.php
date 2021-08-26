<?php

namespace Suenerds\ArcanistRestApiRenderer\Fields;

use Illuminate\Support\Arr;

class CheckboxGroup extends Field
{
    public string $component = 'CheckboxGroup';

    public array $options = [];
    public $default = [];

    public function options($options): self
    {
        if (is_callable($options)) {
            $options = $options();
        }

        $this->options = collect($options)->map(function ($attribute, $label) {
            if (!is_array($attribute)) {
                $attribute = [$attribute, null];
            }
            [$value, $description] = $attribute;

            return ['label' => $label, 'value' => $value, 'description' => $description];
        })->values()->all();

        return $this;
    }

    public function readOnly(): self
    {
        parent::readOnly();

        $this->displayUsing(function ($value) {
            return collect($this->options)->filter(function ($item) use ($value) {
                return in_array($item['value'], $value);
            })->map(function ($item) {
                return Arr::only($item, ['label', 'description']);
            })->values()->all();
        });

        return $this;
    }


    public function jsonSerialize(): array
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'options' => $this->options,
            ]
        );
    }
}
