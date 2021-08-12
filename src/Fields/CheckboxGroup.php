<?php

namespace Suenerds\ArcanistRestApiRenderer\Fields;

class CheckboxGroup extends Field
{
    public string $component = 'CheckboxGroup';

    public $options = [];
    public $defaults = [];

    public function options($options = [])
    {
        if (is_callable($options)) {
            $options = $options();
        }

        $this->options = collect($options)->map(function ($attribute, $label) {
            if (is_string($attribute)) {
                $attribute = [$attribute, null];
            }

            [$value, $description] = $attribute;

            return [ 'label' => $label, 'value' => $value, 'description' => $description];
        })->values()->all();

        return $this;
    }

    public function defaults($defaults)
    {
        $this->defaults = $defaults;

        return $this;
    }

    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'defaults' => $this->defaults,
                'options' => $this->options,
            ]
        );
    }
}
