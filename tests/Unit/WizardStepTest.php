<?php

namespace Suenerds\ArcanistRestApiRenderer\Tests\Unit;

use Mockery as m;
use Arcanist\AbstractWizard;
use Illuminate\Http\Request;
use PHPUnit\Util\Test;
use Suenerds\ArcanistRestApiRenderer\WizardStep;
use Suenerds\ArcanistRestApiRenderer\Fields\Field;
use Suenerds\ArcanistRestApiRenderer\Tests\TestCase;

class WizardStepTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->wizard = m::mock(AbstractWizard::class);
        $this->wizard->allows('data')->with('editable', null)->andReturn('::editable::');
        $this->wizard->allows('data')->with('not_editable', null)->andReturn('::not_editable::');

        $this->step = (new TestStep())->init($this->wizard, 1);
    }

    /** @test */
    public function it_doesnt_process_read_only_fields()
    {
        $request = Request::create(
            uri: '/url',
            method: 'POST',
            content: json_encode([
                'editable' => '::set_editable::',
                'not_editable' => '::try_setting_not_editable::',
            ])
        );
        $request->headers->set('content-type', 'application/json');

        $payload = $this->step->process($request)->payload();
        $this->assertArrayNotHasKey('not_editable', $payload);
        $this->assertEquals('::set_editable::', $payload['editable']);
    }

    /** @test */
    public function it_transforms_data_for_display()
    {
        $this->assertEquals('::EDITABLE::', $this->step->viewData(new Request())['editable']);
        $this->assertEquals('::not_editable::', $this->step->viewData(new Request())['not_editable']);
    }
}

class TestStep extends WizardStep
{
    public function fields(): array
    {
        return [
            Field::make('editable')->displayUsing(fn ($value) => strtoupper($value)),
            Field::make('not_editable')->readOnly(),
        ];
    }
}
