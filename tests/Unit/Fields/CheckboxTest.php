<?php

namespace Suenerds\ArcanistRestApiRenderer\Tests\Unit\Fields;

use Suenerds\ArcanistRestApiRenderer\Tests\TestCase;
use Suenerds\ArcanistRestApiRenderer\Fields\Checkbox;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckboxTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_initialized_with_an_option()
    {
        $checkbox = Checkbox::make('::name::')
            ->option([
                '::label::',
                '::description::',
            ]);

        $this->assertEquals(
            [
                'label' => '::label::',
                'description' => '::description::',
            ],
            $checkbox->option
        );
    }

    /** @test */
    public function it_correctly_displays_false_if_no_default_or_value_is_set()
    {
        $field = Checkbox::make('::name::');
            
        $this->assertEquals(false, $field->display(null));
    }

    /** @test */
    public function it_serializes_correctly()
    {
        $checkbox = Checkbox::make('::name::')
            ->option([
                '::label::',
                '::description::',
            ]);

        $this->assertEquals(
            [
                'name' => '::name::',
                'rules' => ['nullable'],
                'dependencies' => [],
                'component' => 'Checkbox',
                'meta' => [],
                'readOnly' => false,
                'label' => '',
                'option' => [
                    'label' => '::label::',
                    'description' => '::description::',
                ]
            ],
            $checkbox->JsonSerialize()
        );
    }
}
