<?php

namespace Suenerds\ArcanistRestApiRenderer\Tests\Unit\Fields;

use Suenerds\ArcanistRestApiRenderer\Tests\TestCase;
use Suenerds\ArcanistRestApiRenderer\Fields\Text;

class TextTest extends TestCase
{
    /** @test */
    public function it_can_be_initialized_with_the_placeholder_attribute()
    {
        $textField = Text::make('::name::')
            ->placeholder('::placeholder::');

        $this->assertEquals('::placeholder::', $textField->placeholder);
    }

    /** @test */
    public function it_serializes()
    {
        $field =  Text::make('::name::')
                    ->placeholder('::placeholder::');

        $this->assertEquals(
            [
                'name' => '::name::',
                'rules' => ['nullable'],
                'dependencies' => [],
                'component' => 'Text',
                'meta' => [],
                'placeholder' => '::placeholder::',
                'readOnly' => false,
                'label' => '',
                'description' => '',
            ],
            $field->JsonSerialize()
        );
    }
}
