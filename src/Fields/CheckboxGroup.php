<?php

namespace Suenerds\ArcanistRestApiRenderer\Fields;

class CheckboxGroup extends Field
{
    public string $component = 'CheckboxGroup';

    public array $options = [];
    public $default = [];
    
    public function options($options) : self
    {
        if (is_callable($options)) {
            $options = $options();
        }

        $this->options = collect($options)->map(function ($attribute, $label) {
            if (!is_array($attribute)) {
                $attribute = [$attribute, null];
            }
            [$value, $description] = $attribute;

            return [ 'label' => $label, 'value' => $value, 'description' => $description];
        })->values()->all();

        return $this;
    }


    public function jsonSerialize() : array
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'options' => $this->options,
            ]
        );
    }
}
