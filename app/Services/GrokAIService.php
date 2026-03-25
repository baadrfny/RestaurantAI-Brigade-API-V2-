<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GrokAIService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('GROK_API_KEY');
        $this->baseUrl = 'https://api.x.ai/v1';
    }

    /**
     * Analyze plate compatibility using Grok AI
     */
    public function analyzePlateCompatibility(array $plate, array $ingredients, array $restrictions): array
    {
        try {
            $prompt = $this->buildPrompt($plate, $ingredients, $restrictions);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => 'grok-4-1-fast',
                'messages' => [
                    [
        'role' => 'system',
        'content' => 'You are a strict nutrition expert. You MUST calculate the score starting at 100 and subtracting exactly 25 for each conflict. You MUST provide the warning_message in FRENCH if the score is below 100.'
    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.1,
                'max_tokens' => 200
            ]);

            if (!$response->successful()) {
                Log::error('Grok AI API Error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return $this->getDefaultResponse();
            }

            $aiResponse = $response->json('choices.0.message.content');
            return $this->parseResponse($aiResponse);

        } catch (\Exception $e) {
            Log::error('Grok AI Service Error', [
                'error' => $e->getMessage(),
                'plate' => $plate,
                'restrictions' => $restrictions
            ]);
            return $this->getDefaultResponse();
        }
    }

    /**
     * Build the AI prompt for dietary analysis
     */
    private function buildPrompt(array $plate, array $ingredients, array $restrictions): string
    {
        $dishName = $plate['name'] ?? 'Unknown Dish';
        $ingredientTags = $this->formatIngredientTags($ingredients);
        $restrictionList = implode(', ', $restrictions);

        return <<<PROMPT
Analyze the nutritional compatibility between this dish and the user's dietary restrictions.

DISH: {$dishName}
INGREDIENT TAGS: {$ingredientTags}
USER RESTRICTIONS: {$restrictionList}

Tag mapping rules:
"vegan" restriction conflicts with: contains_meat, contains_lactose, animal, dairy
"no_sugar" restriction conflicts with: contains_sugar
"no_cholesterol" restriction conflicts with: contains_cholesterol
"gluten_free" restriction conflicts with: contains_gluten
"no_lactose" restriction conflicts with: contains_lactose, dairy
"vegetarian" restriction conflicts with: contains_meat, animal

Calculate score: start at 100, subtract 25 for each conflict found.

Respond ONLY with this JSON (no markdown, no explanation):
{"score": <0-100>, "warning_message": "<in French if score < 50, else empty string>"}
PROMPT;
    }

    /**
     * Format ingredient tags for the prompt
     */
    private function formatIngredientTags(array $ingredients): string
    {
        $tags = [];
        foreach ($ingredients as $ingredient) {
            if (isset($ingredient['tags']) && is_array($ingredient['tags'])) {
                $tags = array_merge($tags, $ingredient['tags']);
            }
        }
        return implode(', ', array_unique($tags));
    }

    /**
     * Parse AI response and return structured data
     */
    private function parseResponse(string $response): array
    {
        try {
            // Clean response - remove any markdown formatting
            $cleanResponse = preg_replace('/```json\s*|\s*```/', '', trim($response));
            
            $data = json_decode($cleanResponse, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON Parse Error', [
                    'response' => $response,
                    'error' => json_last_error_msg()
                ]);
                return $this->getDefaultResponse();
            }

            // Validate and sanitize the response
            $score = max(0, min(100, (int)($data['score'] ?? 50)));
            $warningMessage = empty($data['warning_message']) ? null : $data['warning_message'];

            return [
                'score' => $score,
                'warning_message' => $warningMessage,
                'label' => $this->getLabel($score)
            ];

        } catch (\Exception $e) {
            Log::error('Response Parse Error', [
                'error' => $e->getMessage(),
                'response' => $response
            ]);
            return $this->getDefaultResponse();
        }
    }

    /**
     * Get label based on score
     */
    private function getLabel(int $score): string
    {
        if ($score >= 80) return 'Excellent Match';
        if ($score >= 60) return 'Good Match';
        if ($score >= 40) return 'Fair Match';
        return 'Poor Match';
    }

    /**
     * Default response when AI fails
     */
    private function getDefaultResponse(): array
    {
        return [
            'score' => 50,
            'warning_message' => null,
            'label' => 'Fair Match'
        ];
    }
}
