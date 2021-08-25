<?php

namespace Suenerds\ArcanistRestApiRenderer\Fields;

class Checkbox extends Field
{
    public string $component = 'Checkbox';

    public $option = [];

    public function option($option)
    {
        [$label, $description] = $option;

        $this->option = ['label' => $label, 'description' => $description];

        return $this;
    }

    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'option' => $this->option,
            ]
        );
    }
}
