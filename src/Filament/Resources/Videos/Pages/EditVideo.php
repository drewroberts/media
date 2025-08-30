<?php

namespace DrewRoberts\Media\Filament\Resources\Videos\Pages;

use DrewRoberts\Media\Facades\YouTube;
use DrewRoberts\Media\Filament\Resources\Videos\VideoResource;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;

class EditVideo extends EditRecord
{
    protected static string $resource = VideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            Actions\Action::make('refreshApiData')
                ->label('Refresh API Data')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function (): void {
                    $record = $this->getRecord();
                    if (! $record instanceof \DrewRoberts\Media\Models\Video || ($record->source ?? 'youtube') !== 'youtube' || empty($record->identifier)) {
                        Notification::make()->title('Cannot refresh')->body('This record does not have a YouTube ID.')->warning()->send();

                        return;
                    }

                    try {
                        $video = YouTube::fetch($record->identifier);

                        $record->title = $video->title;
                        $record->duration = $video->durationSeconds;
                        $record->published_at = $video->publishedAt ? Carbon::make($video->publishedAt) : null;
                        $record->view_count = $video->viewCount;
                        $record->like_count = $video->likeCount;
                        $record->comment_count = $video->commentCount;
                        $record->privacy = $video->privacyStatus;
                        $record->embeddable = $video->embeddable ?? $record->embeddable;
                        $record->broadcast = $video->broadcast;

                        if (empty($record->image_id)) {
                            $image = YouTube::ensureThumbnailImage($video);
                            if ($image) {
                                $record->image_id = $image->id;
                            }
                        }

                        $record->save();
                        $record->refresh();
                        $this->fillForm();

                        Notification::make()->title('Refreshed from YouTube')->success()->send();
                    } catch (\Throwable $e) {
                        Notification::make()->title('Refresh failed')->body($e->getMessage())->danger()->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Refresh video data from YouTube?')
                ->modalSubmitActionLabel('Refresh')
                ->modalCancelActionLabel('Cancel'),
            $this->getCancelFormAction(),
        ];
    }
}
