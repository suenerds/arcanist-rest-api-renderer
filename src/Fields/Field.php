<?php

namespace Suenerds\ArcanistRestApiRenderer\Fields;

use Arcanist\Field as ArcanistField;
use JsonSerializable;

class Field extends ArcanistField implements JsonSerializable
{
    public string $component = 'Field';

    public array $meta = [];

    public string $label = '';
    public $default = '';

    protected $displayCallback = null;
    protected $readOnly = false;

    public function __construct(
        public string $name,
        public array $rules = ['nullable'],
        public array $dependencies = []
    ) {
        $this->displayUsing(function ($value) {
            if (is_null($value)) {
                return $this->default;
            }

            return $value;
        });
    }

    public function default($default) : self
    {
        $this->default = $default;

        return $this;
    }

    public function isEditable() : bool
    {
        return ! $this->readOnly;
    }

    public function isReadOnly() : bool
    {
        return $this->readOnly;
    }

    public function readOnly() : self
    {
        $this->readOnly = true;

        return $this;
    }

    public function display(mixed $value): mixed
    {
        $callback = $this->displayCallback ?: fn ($val) => $val;

        return $callback($value, $this);
    }

    public function displayUsing(callable $callback) : self
    {
        $this->displayCallback = $callback;

        return $this;
    }

    public function label(string $label) : self
    {
        $this->label = $label;

        return $this;
    }

    public function meta($meta) : Field
    {
        if (is_callable($meta)) {
            $meta = $meta();
        }

        if (is_array($meta) == false) {
            $meta = [$meta];
        }

        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    public function jsonSerialize() : array
    {
        return [
            'name' => $this->name,
            'rules' => $this->rules,
            'dependencies' => $this->dependencies,
            'component' => $this->component,
            'meta' => $this->meta,
            'readOnly' => $this->readOnly,
            'label' => $this->label,
        ];
    }
}
