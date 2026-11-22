<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\PostStatus;
use App\Filament\Schemas\TitleSlugFields;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make()
                    ->columnSpan(2)
                    ->schema([
                        ...TitleSlugFields::make(),
                        RichEditor::make('content')
                            ->label('Konten')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('posts/attachments')
                            ->fileAttachmentsVisibility('public'),
                    ]),
                Section::make()
                    ->columnSpan(1)
                    ->schema([
                        FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('posts')
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyResizeImagesToWidth('1920')
                            ->automaticallyResizeImagesToHeight('1920')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->visibility('public')
                            ->maxSize(4096),
                        Select::make('status')
                            ->options(PostStatus::class)
                            ->default(PostStatus::Draft)
                            ->required()
                            ->native(false),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal terbit')
                            ->helperText('Kosongkan untuk diisi otomatis saat status Published.'),
                    ]),
            ]);
    }
}
