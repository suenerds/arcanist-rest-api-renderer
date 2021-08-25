<?php

namespace Suenerds\ArcanistRestApiRenderer\Fields;

use Illuminate\Support\Arr;

class RadioGroup extends Field
{
    public string $component = 'RadioGroup';

    public $options = [];
    public string $description = '';

    public function description($description) : self
    {
        $this->description = $description;
        return $this;
    }

    public function options($options) : self
    {
        if (is_callable($options)) {
            $options = $options();
        }
        
        $this->options = collect($options)->when(!Arr::isAssoc($options), function ($collection) {
            return $collection->mapWithKeys(function ($value) {
                return [ $value => $value ];
            });
        })->map(function ($value, $label) {
            return [ 'label' => $label, 'value' => $value];
        })->values()->all();
            
        return $this;
    }

    public function jsonSerialize() : array
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'description' => $this->description,
                'options' => $this->options,
            ]
        );
    }
}
