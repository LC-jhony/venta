<?php

namespace App\Filament\Pages;

use App\Models\Setting as ModelsSetting;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class Setting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.pages.setting';
    protected static ?string $navigationGroup = 'Systemm';
    protected static ?int $navigationSort = 2;
    protected static bool $shouldRegisterNavigation = true;
    public ?array $data = [];
    public ?ModelsSetting $settings = null;
    public function mount(): void
    {
        $this->settings = ModelsSetting::first();
        if ($this->settings) {
            $this->form->fill($this->settings->toArray());
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->default(fn() => ModelsSetting::first()?->logo ?? null)
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->required(),
                        Forms\Components\TextInput::make('commercial_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('company_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('ruc')
                            ->required(),
                        Forms\Components\TextInput::make('type_company')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')

                            ->tel()
                            ->required()
                            ->regex('/^\+?[0-9]{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/'),
                        Forms\Components\TextInput::make('email')
                            ->required()
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('web')


                            ->maxLength(255),
                        Forms\Components\TextInput::make('district')
                            ->label('Distrito')
                            ->required()

                            ->maxLength(255),
                        Forms\Components\TextInput::make('department')
                            ->label('Departamento')
                            ->required()

                            ->maxLength(255),
                    ])
                    ->columns(2)
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        if ($this->settings) {
            $this->settings->update($this->form->getState());
        } else {
            ModelsSetting::create($this->form->getState());
        }
        $this->getSavedNotification()->send();
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title('Settings saved successfully')
            ->send();
    }
}
