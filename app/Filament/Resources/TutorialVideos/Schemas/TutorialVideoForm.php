<?php

namespace App\Filament\Resources\TutorialVideos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TutorialVideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('video_url')
                    ->label('Video')
                    ->disk('public')
                    ->directory('tutorial-videos/videos')
                    ->visibility('public')
                    ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/webm'])
                    ->maxSize(102400)
                    ->required(),
                FileUpload::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->directory('tutorial-videos/thumbnails')
                    ->visibility('public')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(10240),
                Select::make('gender_target')
                    ->options([
                        'boy' => 'Boy',
                        'girl' => 'Girl',
                        'both' => 'Both',
                    ])
                    ->required()
                    ->default('both'),
                Select::make('muscle_group')
                    ->options([
                        'chest' => 'Chest',
                        'back' => 'Back',
                        'shoulders' => 'Shoulders',
                        'arms' => 'Arms',
                        'legs' => 'Legs',
                        'core' => 'Core',
                        'full_body' => 'Full Body',
                    ])
                    ->required(),
            ]);
    }
}
