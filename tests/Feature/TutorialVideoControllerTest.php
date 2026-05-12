<?php

namespace Tests\Feature;

use App\Models\TutorialVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TutorialVideoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_public_urls_for_stored_tutorial_video_media(): void
    {
        $tutorialVideo = TutorialVideo::factory()->create([
            'video_url' => 'tutorial-videos/videos/push-up.mp4',
            'thumbnail_url' => 'tutorial-videos/thumbnails/push-up.jpg',
            'gender_target' => 'both',
            'muscle_group' => 'chest',
        ]);

        $this->getJson('/api/v1/tutorial-videos')
            ->assertOk()
            ->assertJsonPath('data.0.id', $tutorialVideo->id)
            ->assertJsonPath('data.0.video_url', Storage::disk('public')->url('tutorial-videos/videos/push-up.mp4'))
            ->assertJsonPath('data.0.thumbnail_url', Storage::disk('public')->url('tutorial-videos/thumbnails/push-up.jpg'));

        $this->getJson("/api/v1/tutorial-videos/{$tutorialVideo->id}")
            ->assertOk()
            ->assertJson([
                'id' => $tutorialVideo->id,
                'video_url' => Storage::disk('public')->url('tutorial-videos/videos/push-up.mp4'),
                'thumbnail_url' => Storage::disk('public')->url('tutorial-videos/thumbnails/push-up.jpg'),
            ]);
    }
}
