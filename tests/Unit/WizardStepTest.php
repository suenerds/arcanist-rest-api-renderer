<?php

namespace Suenerds\ArcanistRestApiRenderer\Tests\Unit;

use Mockery as m;
use Arcanist\AbstractWizard;
use Illuminate\Http\Request;
use Suenerds\ArcanistRestApiRenderer\WizardStep;
use Suenerds\ArcanistRestApiRenderer\Fields\Field;
use Suenerds\ArcanistRestApiRenderer\Tests\TestCase;

class WizardStepTest extends TestCase
{
    /** @test */
    public function it_doesnt_process_read_only_fields()
    {
        $step = new TestStep();

        // TODO: cleanup
        $request = Request::create('/url', 'POST', [], [], [], [], json_encode([
            'editable' => 'lululu',
            'not_editable' => 'lalala',
        ]));
        $request->headers->set('content-type', 'application/json');

        $this->assertArrayNotHasKey('not_editable', $step->process($request)->payload());
    }

    /** @test */
    public function it_uses_the_display_method_in_the_view_data()
    {
        $this->markTestIncomplete();

        $wizard = m::mock(TestWizard::class)->makePartial();
        $wizard->allows('summary')
            ->andReturns(['::summary::']);
        $this->wizard = $wizard->makePartial();

        // TODO: cleanup
        $request = Request::create('/url', 'POST', [], [], [], [], json_encode([
            'editable' => 'lululu',
            'not_editable' => 'lalala',
        ]));
        $request->headers->set('content-type', 'application/json');

        $this->assertEquals('::VALUE::', $this->wizard->currentStep()->viewData($request)['formData']['editable']);
    }
}

class TestStep extends WizardStep
{
    public function fields(): array
    {
        return [
            Field::make('editable')->displayUsing(fn($value) => strtoupper($value)),
            Field::make('not_editable')->readOnly(),
        ];
    }
}

class TestWizard extends AbstractWizard
{
    public static string $slug = 'test-wizard';
    protected mixed $id = 1;

    public array $steps = [
        TestStep::class,
    ];
}
