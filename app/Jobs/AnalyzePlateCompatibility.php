<?php

namespace App\Jobs;

use App\Models\Recommendation;
use App\Services\GrokAIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class AnalyzePlateCompatibility implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userId;
    private $plateId;
    private $plateData;
    private $userRestrictions;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, int $plateId, array $plateData, array $userRestrictions)
    {
        $this->userId = $userId;
        $this->plateId = $plateId;
        $this->plateData = $plateData;
        $this->userRestrictions = $userRestrictions;
    }

    /**
     * Execute the job.
     */
    public function handle(GrokAIService $grokService): void
    {
        try {
            // Get plate ingredients
            $plate = \App\Models\Plat::with('ingredients')->find($this->plateId);
            
            if (!$plate) {
                \Log::error('Plate not found for AI analysis', ['plate_id' => $this->plateId]);
                return;
            }

            // Prepare ingredients data for AI
            $ingredientsData = $plate->ingredients->map(function ($ingredient) {
                return [
                    'name' => $ingredient->name,
                    'tags' => $ingredient->tags ?? []
                ];
            })->toArray();

            // Call Grok AI for analysis
            $analysis = $grokService->analyzePlateCompatibility(
                $this->plateData,
                $ingredientsData,
                $this->userRestrictions
            );

            // Update or create recommendation
            Recommendation::updateOrCreate(
                [
                    'user_id' => $this->userId,
                    'plate_id' => $this->plateId,
                ],
                [
                    'status' => 'ready',
                    'score' => $analysis['score'],
                    'label' => $analysis['label'],
                    'warning_message' => $analysis['warning_message'],
                ]
            );

            \Log::info('AI Analysis completed', [
                'user_id' => $this->userId,
                'plate_id' => $this->plateId,
                'score' => $analysis['score']
            ]);

        } catch (\Exception $e) {
            \Log::error('AI Analysis Job Failed', [
                'error' => $e->getMessage(),
                'user_id' => $this->userId,
                'plate_id' => $this->plateId
            ]);

            // Create a fallback recommendation
            Recommendation::updateOrCreate(
                [
                    'user_id' => $this->userId,
                    'plate_id' => $this->plateId,
                ],
                [
                    'status' => 'ready',
                    'score' => 50,
                    'label' => 'Fair Match',
                    'warning_message' => 'AI analysis temporarily unavailable',
                ]
            );
        }
    }

    /**
     * Get the tags that should be monitored for this job.
     */
    public function tags(): array
    {
        return ['ai-analysis', 'user:' . $this->userId, 'plate:' . $this->plateId];
    }
}
