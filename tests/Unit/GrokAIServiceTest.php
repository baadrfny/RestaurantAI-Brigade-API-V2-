<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\GrokAIService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GrokAIServiceTest extends TestCase
{
    private GrokAIService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GrokAIService();
    }

    /**
     * Test the service with a mock successful API response
     */
    public function test_analyze_plate_compatibility_success(): void
    {
        // Mock the HTTP response
        Http::fake([
            'api.groq.com/openai/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => '{"score": 100, "warning_message": ""}'
                        ]
                    ]
                ]
            ], 200)
        ]);

        $plate = ['name' => 'Salade Verte'];
        $ingredients = [
            ['tags' => ['vegan', 'gluten-free', 'healthy']]
        ];
        $restrictions = ['vegan', 'gluten-free'];

        $result = $this->service->analyzePlateCompatibility($plate, $ingredients, $restrictions);

        $this->assertEquals(100, $result['score']);
        $this->assertEquals('', $result['warning_message']);
        $this->assertEquals('Excellent Match', $result['label']);
    }

    /**
     * Test the service with dietary conflicts
     */
    public function test_analyze_plate_compatibility_with_conflicts(): void
    {
        Http::fake([
            'api.groq.com/openai/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => '{"score": 50, "warning_message": "Ce plat contient de la viande, ce qui est incompatible avec un régime végétalien."}'
                        ]
                    ]
                ]
            ], 200)
        ]);

        $plate = ['name' => 'Steak Frites'];
        $ingredients = [
            ['tags' => ['contains_meat', 'contains_dairy']]
        ];
        $restrictions = ['vegan', 'vegetarian'];

        $result = $this->service->analyzePlateCompatibility($plate, $ingredients, $restrictions);

        $this->assertEquals(50, $result['score']);
        $this->assertStringContainsString('viande', $result['warning_message']);
        $this->assertEquals('Fair Match', $result['label']);
    }

    /**
     * Test API failure handling
     */
    public function test_analyze_plate_compatibility_api_failure(): void
    {
        Http::fake([
            'api.groq.com/openai/v1/chat/completions' => Http::response('API Error', 500)
        ]);

        $plate = ['name' => 'Test Dish'];
        $ingredients = [['tags' => ['test']]];
        $restrictions = ['test'];

        $result = $this->service->analyzePlateCompatibility($plate, $ingredients, $restrictions);

        $this->assertEquals(50, $result['score']);
        $this->assertEquals('Analyse indisponible pour le moment.', $result['warning_message']);
        $this->assertEquals('Fair Match', $result['label']);
    }

    /**
     * Test JSON parsing with markdown formatting
     */
    public function test_parse_response_with_markdown(): void
    {
        Http::fake([
            'api.groq.com/openai/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => '```json
{"score": 75, "warning_message": "Attention : contient des traces de noix."}
```'
                        ]
                    ]
                ]
            ], 200)
        ]);

        $plate = ['name' => 'Test Dish'];
        $ingredients = [['tags' => ['contains_nuts']]];
        $restrictions = ['nut-free'];

        $result = $this->service->analyzePlateCompatibility($plate, $ingredients, $restrictions);

        $this->assertEquals(75, $result['score']);
        $this->assertStringContainsString('noix', $result['warning_message']);
        $this->assertEquals('Good Match', $result['label']);
    }

    /**
     * Test score boundaries
     */
    public function test_score_boundaries(): void
    {
        // Test score > 100 gets capped
        Http::fake([
            'api.groq.com/openai/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => '{"score": 150, "warning_message": ""}'
                        ]
                    ]
                ]
            ], 200)
        ]);

        $result = $this->service->analyzePlateCompatibility(['name' => 'Test'], [['tags' => ['test']]], ['test']);
        $this->assertEquals(100, $result['score']);
    }

    /**
     * Test score lower boundary
     */
    public function test_score_lower_boundary(): void
    {
        // Test score < 0 gets floored
        Http::fake([
            'api.groq.com/openai/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => '{"score": -10, "warning_message": "Very bad"}'
                        ]
                    ]
                ]
            ], 200)
        ]);

        $result = $this->service->analyzePlateCompatibility(['name' => 'Test'], [['tags' => ['test']]], ['test']);
        $this->assertEquals(0, $result['score']);
    }
}
