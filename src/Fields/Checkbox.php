<?php

namespace Suenerds\ArcanistRestApiRenderer\Fields;

class Checkbox extends Field
{
    public string $component = 'Checkbox';

    public $option = [];
    public $default = false;

    public function option($option) : self
    {
        [$label, $description] = $option;

        $this->option = ['label' => $label, 'description' => $description];

        return $this;
    }

    public function jsonSerialize() : array
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'option' => $this->option,
            ]
        );
    }
}
