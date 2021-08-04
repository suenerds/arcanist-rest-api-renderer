<?php

namespace Suenerds\ArcanistRestApiRenderer\Fields;

class RadioGroup extends Field
{
    public string $component = 'RadioGroup';
    public string $groupLabel = '';
    public string $groupDescription = '';

    public $options = [];
    public string $default = '';

    public function groupLabel($label)
    {
        $this->groupLabel = $label;
        return $this;
    }
    public function groupDescription($desc)
    {
        $this->groupDescription = $desc;
        return $this;
    }

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

    public function default($default)
    {
        $this->default = $default;
        return $this;
    }

    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'label' => $this->groupLabel,
                'description' => $this->groupDescription,
                'default' => $this->default,
                'options' => $this->options,
            ]
        );
    }
}
