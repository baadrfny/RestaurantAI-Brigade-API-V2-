<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service pour l'analyse nutritionnelle via l'API Groq (Llama-3)
 */
class GrokAIService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('GROQ_API_KEY');
        $this->baseUrl = 'https://api.groq.com/openai/v1';
    }

    /* Analyse la compatibilité d'un plat avec les restrictions alimentaires de l'utilisateur */
    public function analyzePlateCompatibility(array $plate, array $ingredients, array $restrictions): array
    {
        try {
            $prompt = $this->buildPrompt($plate, $ingredients, $restrictions);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Vous êtes un expert en nutrition. Calculez le score sur 100 en soustrayant 25 pour chaque conflit. Fournissez le warning_message en FRANÇAIS. Répondez UNIQUEMENT en JSON.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.5,
                'top_p' => 0.65,
                'max_tokens' => 300
            ]);

            if (!$response->successful()) {
                Log::error('Erreur API Groq', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return $this->getDefaultResponse();
            }

            $aiResponse = $response->json('choices.0.message.content');
            return $this->parseResponse($aiResponse);

        } catch (\Exception $e) {
            Log::error('Exception dans GrokAIService', ['error' => $e->getMessage()]);
            return $this->getDefaultResponse();
        }
    }

    /**
     * Construit le prompt structuré pour l'IA
     */
    private function buildPrompt(array $plate, array $ingredients, array $restrictions): string
    {
        $dishName = $plate['name'] ?? 'Plat inconnu';
        $ingredientTags = $this->formatIngredientTags($ingredients);
        $restrictionList = implode(', ', $restrictions);

        return <<<PROMPT
Analysez la compatibilité nutritionnelle :
PLAT : {$dishName}
TAGS INGRÉDIENTS : {$ingredientTags}
RESTRICTIONS UTILISATEUR : {$restrictionList}

Règles :
1. Score initial : 100.
2. Soustrayez 25 pour chaque conflit (ex: "vegan" vs "contains_meat").
3. Si score < 100, rédigez un "warning_message" en FRANÇAIS expliquant le conflit.
4. Si score == 100, "warning_message" doit être une chaîne vide.

Format de réponse attendu (JSON UNIQUEMENT) :
{"score": <int>, "warning_message": "<string>"}
PROMPT;
    }

    /**
     * Formate les tags des ingrédients en une chaîne unique
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
     * Analyse et nettoie la réponse JSON de l'IA
     */
    private function parseResponse(string $response): array
    {
        try {
            // Nettoyage du Markdown éventuel (```json ... ```)
            $cleanResponse = preg_replace('/```json\s*|\s*```/', '', trim($response));
            $data = json_decode($cleanResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON invalide reçu de l\'IA');
            }

            $score = max(0, min(100, (int)($data['score'] ?? 50)));

            return [
                'score' => $score,
                'warning_message' => $data['warning_message'] ?? null,
                'label' => $this->getLabel($score)
            ];

        } catch (\Exception $e) {
            Log::error('Erreur de parsing de la réponse', ['response' => $response]);
            return $this->getDefaultResponse();
        }
    }

    /**
     * Retourne un label textuel basé sur le score
     */
    private function getLabel(int $score): string
    {
        if ($score >= 80) return 'Excellent Match';
        if ($score >= 60) return 'Good Match';
        if ($score >= 40) return 'Fair Match';
        return 'Poor Match';
    }

    /**
     * Réponse par défaut en cas d'échec de l'IA
     */
    private function getDefaultResponse(): array
    {
        return [
            'score' => 50,
            'warning_message' => 'Analyse indisponible pour le moment.',
            'label' => 'Fair Match'
        ];
    }
}