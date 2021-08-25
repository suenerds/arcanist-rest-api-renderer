<?php

namespace Suenerds\ArcanistRestApiRenderer\Fields;

class Select extends Field
{
    public string $component = 'Select';
    public array $options = [];

    public function options($options) : self
    {
        if (is_callable($options)) {
            $options = $options();
        }

        $this->options = collect($options)->map(function ($value, $label) {
            return [ 'label' => (string) $label, 'value' => (string) $value ];
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
