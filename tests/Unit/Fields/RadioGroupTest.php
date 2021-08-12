<?php

namespace Suenerds\ArcanistRestApiRenderer\Tests\Unit\Fields;

use Suenerds\ArcanistRestApiRenderer\Tests\TestCase;
use Suenerds\ArcanistRestApiRenderer\Fields\RadioGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RadioGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_initialized_with_an_group_label()
    {
        $radioGroup = RadioGroup::make('::name::')
            ->groupLabel('::label::');

        $this->assertEquals('::label::', $radioGroup->groupLabel);
    }

    /** @test */
    public function it_can_be_initialized_with_an_group_description()
    {
        $radioGroup = RadioGroup::make('::name::')
            ->groupDescription('::description::');

        $this->assertEquals('::description::', $radioGroup->groupDescription);
    }

    /** @test */
    public function it_can_be_initialized_with_an_options_array()
    {
        $radioGroup = RadioGroup::make('::name::')
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
            ], $radioGroup->options);
    }

    /** @test */
    public function it_can_be_initialized_with_an_options_array_without_descriptions()
    {
        $radioGroup = RadioGroup::make('::name::')
            ->options([
                'label1' => 'value1',
                'label2' => 'value2',
            ]);

        $this->assertEquals([
            [
                'label' => 'label1',
                'value' => 'value1',
                'description' => null
            ],
            [
                'label' => 'label2',
                'value' => 'value2',
                'description' => null
            ],
        ], $radioGroup->options);
    }

    /** @test */
    public function it_can_be_initialized_with_an_options_arrow_function_without_description()
    {
        $radioGroup = RadioGroup::make('::name::')
            ->options(fn () => ['::label::' => '::value::']);

        $this->assertEquals([
            [
                'label' => '::label::',
                'value' => '::value::',
                'description' => null,
            ]
        ], $radioGroup->options);
    }

    /** @test */
    public function it_can_be_initialized_with_an_default()
    {
        $radioGroup = RadioGroup::make('::name::')
            ->default('::default::');

        $this->assertEquals('::default::', $radioGroup->default);
    }

    /** @test */
    public function it_serializes()
    {
        $field =  RadioGroup::make('::name::')
            ->groupLabel('::groupLabel::')
            ->groupDescription('::description::')
            ->default('::default::')
            ->options([
                '::label::' => ['::value::', '::text::']
            ]);

        $this->assertEquals(
            [
                'name' => '::name::',
                'rules' => ['nullable'],
                'dependencies' => [],
                'component' => 'RadioGroup',
                'meta' => [],
                'label' => '::groupLabel::',
                'description' => '::description::',
                'default' => '::default::',
                'options' => [
                    [
                        'label' => '::label::',
                        'value' => '::value::',
                        'description' => '::text::'
                    ]
                ],
                'readOnly' => false,
            ],
            $field->JsonSerialize()
        );
    }
}
