<?php

namespace Suenerds\ArcanistRestApiRenderer\Fields;

class Text extends Field
{
    public string $component = 'Text';
    public string $placeholder = '';

    public function placeholder($placeholder) : self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function jsonSerialize() : array
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'placeholder' => $this->placeholder,
            ]
        );
    }
}
