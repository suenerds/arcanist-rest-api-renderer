<?php

namespace Suenerds\ArcanistRestApiRenderer\Tests\Unit\Fields;

use Suenerds\ArcanistRestApiRenderer\Tests\TestCase;
use Suenerds\ArcanistRestApiRenderer\Fields\Field;

class FieldTest extends TestCase
{
    /** @test */
    public function it_is_editable_by_default()
    {
        $field = Field::make('::name::');

        $this->assertTrue($field->isEditable());
    }

    /** @test */
    public function it_allows_setting_read_only()
    {
        $field = Field::make('::name::')->readOnly();

        $this->assertFalse($field->isEditable());
    }

    /** @test */
    public function it_doesnt_transform_the_display_value_by_default()
    {
        $field = Field::make('::name::');

        $this->assertEquals('::value::', $field->display('::value::'));
    }

    /** @test */
    public function it_transforms_the_display_value_when_given_a_display_callback()
    {
        $field = Field::make('::name::')
            ->displayUsing(fn($value) => strtoupper($value));

        $this->assertEquals('::VALUE::', $field->display('::value::'));
    }

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
