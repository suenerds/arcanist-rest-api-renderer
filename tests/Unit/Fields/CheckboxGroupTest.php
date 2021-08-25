<?php

namespace Suenerds\ArcanistRestApiRenderer\Tests\Unit\Fields;

use Suenerds\ArcanistRestApiRenderer\Tests\TestCase;
use Suenerds\ArcanistRestApiRenderer\Fields\CheckboxGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckboxGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_initialized_with_an_options_array()
    {
        $checkboxGroup = CheckboxGroup::make('::name::')
            ->options([
                'label1' => ['value1', 'text1'],
                'label2' => ['value2', 'text2']
            ]);

        $this->assertEquals([
                [
                    'label' => 'label1',
                    'value' => 'value1',
                    'description' => 'text1'
                ],
                [
                    'label' => 'label2',
                    'value' => 'value2',
                    'description' => 'text2'
                ],
            ], $checkboxGroup->options);
    }

    /** @test */
    public function it_can_be_initialized_with_an_options_array_without_descriptions()
    {
        $checkboxGroup = CheckboxGroup::make('::name::')
            ->options([
                'label1' => 'value1',
                'label2' => 'value2'
            ]);

        $this->assertEquals([
            [
                'label' => 'label1',
                'value' => 'value1',
                'description' => null,
            ],
            [
                'label' => 'label2',
                'value' => 'value2',
                'description' => null,
            ],
        ], $checkboxGroup->options);
    }

    /** @test */
    public function it_can_be_initialized_with_options_by_passing_an_arrow_function()
    {
        $checkboxGroup = CheckboxGroup::make('::name::')
            ->options(fn () => ['::label::' => '::value::']);

        $this->assertEquals([
            [
                'label' => '::label::',
                'value' => '::value::',
                'description' => null,
            ]
        ], $checkboxGroup->options);
    }

    /** @test */
    public function it_serializes_without_default()
    {
        $field =  CheckboxGroup::make('::name::')
            ->label('::label::')
            ->default(['::default1::', '::default2::'])
            ->options([
                '::label::' => ['::value::', '::text::']
            ]);

        $this->assertEquals(
            [
                'name' => '::name::',
                'rules' => ['nullable'],
                'dependencies' => [],
                'component' => 'CheckboxGroup',
                'meta' => [],
                'options' => [
                    [
                        'label' => '::label::',
                        'value' => '::value::',
                        'description' => '::text::'
                    ]
                ],
                'readOnly' => false,
                'label' => '::label::',
            ],
            $field->JsonSerialize()
        );
    }

    /** @test */
    public function it_can_be_initialized_with_boolean_values()
    {
        $checkboxGroup = CheckboxGroup::make('::name::')
            ->options([
                '::label::' => true,
            ]);

        $this->assertEquals([
            [
                'label' => '::label::',
                'value' => true,
                'description' => null,
            ]
        ], $checkboxGroup->options);
    }

    /** @test */
    public function it_correctly_displays_an_empty_array_if_no_default_or_value_is_set()
    {
        $field = CheckboxGroup::make('::name::');
            
        $this->assertEquals([], $field->display(null));
    }
}
