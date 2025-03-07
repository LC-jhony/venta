<?php

namespace App\Filament\Pages;

use App\Models\Setting as ModelsSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Setting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.pages.setting';

    protected static ?string $navigationGroup = 'Sistema';

    protected static ?string $title = 'Configuración';

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
                Forms\Components\Section::make('Configuración General')
                    ->description('Configuración general para la generación de PDF')
                    ->icon('heroicon-o-cog')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->columns(4)
                            ->schema([
                                Forms\Components\Card::make()
                                    ->columnSpan(1)
                                    ->schema([
                                        Forms\Components\FileUpload::make('logo')
                                            ->label('Logo de la Empresa')
                                            ->image()
                                            ->default(fn () => ModelsSetting::first()?->logo ?? null)
                                            ->disk('public')
                                            ->directory('logo')

                                            ->helperText('Formato: JPG, PNG. Máximo 2MB'),
                                    ]),

                                Forms\Components\Card::make()
                                    ->columnSpan(3)
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('commercial_name')
                                                    ->label('Nombre Comercial')
                                                    ->required()
                                                    ->columnSpan(2),

                                                Forms\Components\TextInput::make('company_name')
                                                    ->label('Nombre de la Compañia')
                                                    ->required()
                                                    ->columnSpan(2),

                                                Forms\Components\TextInput::make('type_company')
                                                    ->label('Tipo de Compañia')
                                                    ->required()
                                                    ->columnSpan(2),
                                            ]),

                                    ]),
                                Forms\Components\Section::make('Datos de Contacto')
                                    ->icon('heroicon-o-phone')
                                    ->compact()
                                    ->columnSpanFull()
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->columns(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('ruc')
                                                    ->label('RUC')
                                                    ->placeholder('Ingrese la provincia')
                                                    ->numeric()
                                                    ->required(),
                                                Forms\Components\TextInput::make('address')
                                                    ->label('Direccion')
                                                    ->placeholder('Ingrese el departamento')
                                                    ->required(),
                                                Forms\Components\TextInput::make('phone')
                                                    ->label('Telefono')
                                                    ->placeholder('Ingrese la dirección')
                                                    ->required(),
                                                Forms\Components\Grid::make()
                                                    ->schema([
                                                        Forms\Components\TextInput::make('email')
                                                            ->label('Correo')
                                                            ->placeholder('Ingrese la dirección')
                                                            ->required(),
                                                        Forms\Components\TextInput::make('web')
                                                            ->label('Web')
                                                            ->placeholder('Ingrese la dirección')
                                                            ->required(),
                                                    ]),
                                            ]),
                                    ]),
                                Forms\Components\Section::make('Ubicación')
                                    ->icon('heroicon-o-map-pin')
                                    ->compact()
                                    ->columnSpanFull()
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->columns(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('department')
                                                    ->label('Departamento')
                                                    ->placeholder('Ingrese el departamento')
                                                    ->required(),
                                                Forms\Components\TextInput::make('province')
                                                    ->label('Provincia')
                                                    ->placeholder('Ingrese la dirección')
                                                    ->required(),
                                                Forms\Components\TextInput::make('district')
                                                    ->label('Distrito')
                                                    ->placeholder('Ingrese la provincia')
                                                    ->required(),
                                            ]),
                                    ]),
                            ]),
                    ]),
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
