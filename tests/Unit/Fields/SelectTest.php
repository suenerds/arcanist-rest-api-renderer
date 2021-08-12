<?php

namespace Suenerds\ArcanistRestApiRenderer\Tests\Unit\Fields;

use Suenerds\ArcanistRestApiRenderer\Tests\TestCase;
use Suenerds\ArcanistRestApiRenderer\Fields\Select;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SelectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_initialized_with_an_options_array()
    {
        $select = Select::make('::name::')
            ->options([
                'label1' => 'value1',
                'label2' => 'value2',
            ]);

        $this->assertEquals([
            [
                'label' => 'label1',
                'value' => 'value1'
            ],
            [
                'label' => 'label2',
                'value' => 'value2'
            ],
        ], $select->options);
    }

    /** @test */
    public function it_can_be_initialized_with_an_options_arrow_function()
    {
        $select = Select::make('::name::')
            ->options(fn () => ['::label::' => '::value::']);

        $this->assertEquals([
            [
                'label' => '::label::',
                'value' => '::value::',
            ]
        ], $select->options);
    }

    /** @test */
    public function it_serializes()
    {
        $field =  Select::make('::name::')
                ->options([
                    '::label::' => '::value::',
                ]);

        $this->assertEquals(
            [
                'name' => '::name::',
                'rules' => ['nullable'],
                'dependencies' => [],
                'component' => 'Select',
                'meta' => [],
                'options' => [
                    [
                        'label' => '::label::',
                        'value' => '::value::',
                    ]
                ],
                'readOnly' => false,
                'label' => '',
            ],
            $field->jsonSerialize()
        );
    }
}
