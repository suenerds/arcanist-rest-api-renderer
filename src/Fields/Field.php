<?php

namespace Suenerds\ArcanistRestApiRenderer\Fields;

use Arcanist\Field as ArcanistField;
use JsonSerializable;

class Field extends ArcanistField implements JsonSerializable
{
    public string $component = 'Field';

    public array $meta = [];

    protected bool $editable = true;
    protected $displayCallback = null;

    public function isEditable()
    {
        return $this->editable;
    }

    public function readOnly()
    {
        $this->editable = false;
        return $this;
    }

    public function display(mixed $value): mixed
    {
        $callback = $this->displayCallback ?: fn ($val) => $val;
        return $callback($value);
    }

    public function displayUsing(callable $callback): self
    {
        $this->displayCallback = $callback;
        return $this;
    }

    public function meta($meta) : Field
    {
        if (is_callable($meta)) {
            $meta = $meta();
        }

        if (is_array($meta)==false) {
            $meta = array($meta);
        }

        $this->meta = array_merge($this->meta, $meta);
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'rules' => $this->rules,
            'dependencies' => $this->dependencies,
            'component' => $this->component,
            'meta' => $this->meta,
        ];
    }
}
