<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Pengaturan Situs';

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    protected static ?string $slug = 'pengaturan';

    /** @var array<string, mixed> */
    public ?array $data = [];

    /** Daftar kunci pengaturan yang dikelola lewat halaman ini. */
    public const KEYS = [
        'site_name',
        'hero_badge',
        'hero_title',
        'hero_subtitle',
        'meta_description',
        'whatsapp_number',
        'business_email',
        'instagram_url',
        'facebook_url',
        'tiktok_url',
        'qris_image',
        'terms_content',
        'google_analytics_id',
        'min_completed_projects_for_stats',
    ];

    public function mount(): void
    {
        $values = [];

        foreach (self::KEYS as $key) {
            $values[$key] = Setting::get($key);
        }

        $this->form->fill($values);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')
                            ->label('Simpan pengaturan')
                            ->submit('save')
                            ->keyBindings(['mod+s']),
                    ])->sticky(),
                ]),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('Pengaturan')
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Identitas & Hero')
                            ->icon(Heroicon::OutlinedSparkles)
                            ->schema([
                                TextInput::make('site_name')
                                    ->label('Nama situs')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('hero_badge')
                                    ->label('Label kecil di atas judul hero')
                                    ->maxLength(255)
                                    ->placeholder('Jasa Pembuatan Website & Aplikasi'),

                                TextInput::make('hero_title')
                                    ->label('Judul hero')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Usahakan singkat, maksimal 10 kata, dan langsung menyebut manfaat.'),

                                Textarea::make('hero_subtitle')
                                    ->label('Subjudul hero')
                                    ->rows(3)
                                    ->maxLength(500),

                                Textarea::make('meta_description')
                                    ->label('Meta description (SEO)')
                                    ->rows(3)
                                    ->maxLength(300)
                                    ->helperText('Muncul sebagai deskripsi di hasil pencarian Google. Ideal 150-160 karakter.'),
                            ]),

                        Tab::make('Kontak & Sosial Media')
                            ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                            ->columns(2)
                            ->schema([
                                TextInput::make('whatsapp_number')
                                    ->label('Nomor WhatsApp')
                                    ->required()
                                    ->tel()
                                    ->maxLength(30)
                                    ->placeholder('081234567890')
                                    ->helperText('Boleh diisi 0812... atau 62812..., sistem otomatis menormalkan ke format internasional.'),

                                TextInput::make('business_email')
                                    ->label('Email bisnis')
                                    ->email()
                                    ->maxLength(255),

                                TextInput::make('instagram_url')
                                    ->label('Link Instagram')
                                    ->url()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-link')
                                    ->helperText('Kosongkan kalau belum punya. Link kosong tidak ditampilkan di footer.'),

                                TextInput::make('facebook_url')
                                    ->label('Link Facebook')
                                    ->url()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-link'),

                                TextInput::make('tiktok_url')
                                    ->label('Link TikTok')
                                    ->url()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-link'),
                            ]),

                        Tab::make('Pembayaran')
                            ->icon(Heroicon::OutlinedCreditCard)
                            ->schema([
                                FileUpload::make('qris_image')
                                    ->label('Gambar QRIS')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pengaturan')
                                    ->maxSize(2048)
                                    ->helperText('Ditampilkan di section metode pembayaran dan di halaman invoice klien.'),

                                Section::make('Rekening bank')
                                    ->description('Daftar rekening dikelola di menu Rekening Bank. Nomor rekening tidak pernah ditampilkan di landing page, hanya di halaman invoice klien.')
                                    ->schema([]),
                            ]),

                        Tab::make('Syarat & Ketentuan')
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                RichEditor::make('terms_content')
                                    ->label('Isi halaman Syarat & Ketentuan')
                                    ->toolbarButtons([
                                        ['bold', 'italic', 'underline', 'strike', 'link'],
                                        ['h2', 'h3', 'bulletList', 'orderedList'],
                                        ['blockquote', 'undo', 'redo'],
                                    ])
                                    ->helperText('Isi minimal: ketentuan DP, revisi, fitur tambahan, kepemilikan source code, dan garansi bug.'),
                            ]),

                        Tab::make('Lain-lain')
                            ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                            ->columns(2)
                            ->schema([
                                TextInput::make('google_analytics_id')
                                    ->label('ID Google Analytics')
                                    ->maxLength(50)
                                    ->placeholder('G-XXXXXXXXXX')
                                    ->helperText('Kosongkan kalau belum dipakai. Script analytics hanya dimuat kalau ID ini diisi.'),

                                TextInput::make('min_completed_projects_for_stats')
                                    ->label('Minimum proyek selesai untuk menampilkan statistik')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(3)
                                    ->required()
                                    ->helperText('Section statistik di landing page disembunyikan selama jumlah proyek selesai masih di bawah angka ini.'),
                            ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::putMany(collect($data)->only(self::KEYS)->all());

        Notification::make()
            ->title('Pengaturan tersimpan')
            ->body('Perubahan langsung tampil di situs.')
            ->success()
            ->send();
    }
}
