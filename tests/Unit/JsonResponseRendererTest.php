<?php

namespace Suenerds\ArcanistRestApiRenderer\Tests;

use Mockery as m;
use Arcanist\WizardStep;
use Arcanist\AbstractWizard;
use Suenerds\ArcanistRestApiRenderer\Tests\TestCase;
use Suenerds\ArcanistRestApiRenderer\Fields\Field;
use Suenerds\ArcanistRestApiRenderer\JsonResponseRenderer;

class JsonResponseRendererTest extends TestCase
{
    private AbstractWizard $wizard;
    private WizardStep $step;
    private JsonResponseRenderer $renderer;
    private Field $field;

    public function setUp(): void
    {
        parent::setUp();

        $wizard = m::mock(JsonTestWizard::class)->makePartial();
        $wizard->allows('summary')
            ->andReturns(['::summary::']);
        $this->wizard = $wizard->makePartial();
        $this->step = new JsonStep;
        $this->renderer = app(JsonResponseRenderer::class);
    }

    /** @test */
    public function it_renders_the_correct_template_for_a_wizard_step()
    {
        $response = $this->renderer->renderStep(
            $this->step,
            $this->wizard,
            ['::key::' => '::value::']
        );

        $this->assertEquals(
            [
                'wizard' => ['::summary::'],
                'fields' => [
                    [
                        'name' => 'test',
                        'rules' => ['nullable'],
                        'dependencies' => [],
                        'component' => 'Field',
                        'meta' => [],
                    ]
                ],
                'step' => [
                    'slug' => 'json-step',
                    'title' => 'New Step'
                ],
                'formData' => ['::key::' => '::value::'],
            ],
            json_decode($response->getContent(), true)
        );
    }

    /** @test */
    public function it_redirects_to_a_steps_view(): void
    {
        $response = $this->renderer->redirect($this->step, $this->wizard);

        $this->assertEquals(
            [
                'redirect'=> [
                'name'=> 'step',
                'params'=> [
                        'wizardSlug'=> 'json-wizard',
                        'wizardId'=> '1',
                        'step'=> 'json-step'
                    ]
                ]
            ],
            json_decode($response->getContent(), true)
        );
    }

    /** @test */
    public function it_redirects_with_error_to_a_steps_view(): void
    {
        $response = $this->renderer->redirectWithError($this->step, $this->wizard, '::error::');

        $this->assertEquals(
            [
                'redirect'=> [
                    'name'=> 'step',
                    'params'=> [
                        'wizardSlug'=> 'json-wizard',
                        'wizardId'=> '1',
                        'step'=> 'json-step'
                    ]
                ],
                'error' => '::error::'
            ],
            json_decode($response->getContent(), true)
        );
    }
}

class JsonTestWizard extends AbstractWizard
{
    public static string $slug = 'json-wizard';
    protected mixed $id = 1;

    protected array $steps = [
        JsonStep::class,
    ];
}

class JsonStep extends WizardStep
{
    public string $slug = 'json-step';

    public function fields(): array
    {
        return [Field::make('test')];
    }

    public function isComplete(): bool
    {
        return false;
    }
}

class SomeOtherStep extends WizardStep
{
    public function isComplete(): bool
    {
        return true;
    }
}
