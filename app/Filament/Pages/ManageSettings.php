<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageSettings extends Page
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Website';

    protected static ?string $title = 'Pengaturan Website';

    protected static ?string $slug = 'settings';

    protected static ?int $navigationSort = 1;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::current()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->model(Setting::class)
            ->columns(2)
            ->components([
                Section::make('Identitas Perusahaan')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('company_name')
                            ->label('Nama perusahaan')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tagline')
                            ->maxLength(255),
                        FileUpload::make('logo')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('settings')
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyResizeImagesToWidth('512')
                            ->automaticallyResizeImagesToHeight('512')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->visibility('public')
                            ->maxSize(2048),
                    ]),
                Section::make('Tentang Kami')
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('about')
                            ->label('Tentang kami')
                            ->rows(5),
                        Textarea::make('vision')
                            ->label('Visi')
                            ->rows(3),
                        Textarea::make('mission')
                            ->label('Misi')
                            ->rows(5)
                            ->helperText('Satu poin per baris.'),
                    ]),
                Section::make('Kontak')
                    ->columns(2)
                    ->columnSpan(1)
                    ->schema([
                        Textarea::make('address')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->helperText('Pesan dari form kontak juga dikirim ke email ini.'),
                    ]),
                Section::make('Media Sosial')
                    ->columnSpan(1)
                    ->schema([
                        Repeater::make('social_links')
                            ->hiddenLabel()
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah link')
                            ->schema([
                                Select::make('platform')
                                    ->options(Setting::SOCIAL_PLATFORMS)
                                    ->required()
                                    ->native(false),
                                TextInput::make('url')
                                    ->label('URL')
                                    ->url()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Simpan')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::current()->fill($data)->save();

        Notification::make()
            ->success()
            ->title('Pengaturan berhasil disimpan')
            ->send();
    }
}
