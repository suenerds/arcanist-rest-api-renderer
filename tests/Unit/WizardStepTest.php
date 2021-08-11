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
        $request = Request::create('/url', 'POST', [], [], [], [], [
            'editable' => 'lululu',
            'not_editable' => 'lalala',
        ]);
      

        $this->assertArrayNotHasKey('not_editable', $step->process($request)->payload());
    }

    /** @test */
    public function it_transforms_data_for_display()
    {
        $step = new class extends WizardStep {
            public function fields(): array
            {
                return [
                    Field::make('editable')->displayUsing(fn ($value) => '::display::'),
                ];
            }
        };
        
        $wizard = m::mock(AbstractWizard::class);
        $wizard->allows('data')->with('editable', null)->andReturn('::editable::');

        $step->init($wizard, 1);
        
        $this->assertEquals('::display::', $step->viewData(new Request())['editable']);
    }
}

class TestStep extends WizardStep
{
    public function fields(): array
    {
        return [
            Field::make('editable')->displayUsing(fn ($value) => '::display::'),
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
