<?php

namespace Suenerds\ArcanistRestApiRenderer\Tests\Unit\Fields;

use Suenerds\ArcanistRestApiRenderer\Tests\TestCase;
use Suenerds\ArcanistRestApiRenderer\Fields\Field;

class FieldTest extends TestCase
{
    /** @test */
    public function it_does_not_overwrite_the_meta_attribute()
    {
        $field = Field::make('::name::')
            ->meta('meta1')
            ->meta('meta2');

        $this->assertEquals(['meta1', 'meta2'], $field->meta);
    }

    /** @test */
    public function it_does_accept_an_arrow_function_for_the_meta_attribute()
    {
        $field = Field::make('::name::')
                ->meta(fn () => ['::Meta::']);
    
        $this->assertEquals(['::Meta::'], $field->meta);
    }

    /** @test */
    public function it_serializes()
    {
        $field = Field::make('::name::')
            ->rules(['required'])
            ->dependsOn('::other-field::')
            ->meta('::meta::');

        $this->assertEquals(
            [
                'name' => '::name::',
                'rules' => ['required'],
                'dependencies' => ['::other-field::'],
                'component' => 'Field',
                'meta' => ['::meta::'],
            ],
            $field->JsonSerialize()
        );
    }
}
