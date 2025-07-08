<?php

namespace App\Livewire\TempMail;

use App\Exceptions\DomainNotFoundException;
use App\Filament\App\Pages\ReadMail;
use App\Models\Domain;
use App\Models\Mail;
use App\Models\Message;
use App\Traits\HasMailable;
use App\Traits\Toastable;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Home extends Component implements HasForms, HasTable
{
    use Toastable;
    use HasMailable, InteractsWithForms, InteractsWithTable;

    public $countMail = 0;

    #[Validate('required|exists:domains,name')]
    public $domain;

    #[Validate('required|min:3')]
    public $customMail;

    public $errorsList = []; // Để lưu trữ lỗi thủ công

    public $domains;

    public ?Mail $selectedMail = null;

    public $mails;

    protected $listeners = [
        'reload' => '$refresh'
    ];

    public function table(Table $table): Table
    {
        $mail = $this->selectedMail;

        return $table
            ->heading(new HtmlString('<span @click="navigator.clipboard.writeText(\'' . $mail?->email . '\')
                  .then(() => $tooltip(\'' . __('Copied!') . '\', {theme:$store.theme}))
                  .catch(() => $tooltip(\'' . __('Copy failed!') . '\', {theme:$store.theme}));">' . $mail?->email . '</span>'))
            ->query(fn(): HasMany|Builder => $mail?->messages() ?? (new Mail())->messages()->whereRaw('0=1'))
            ->inverseRelationship('messages')
            ->columns([
                TextColumn::make('sender_name')
                    ->label('Sender')
                    ->translateLabel()
                    ->description(fn(Message $record): string => $record->from)
                    ->weight(fn(Message $record): string => !$record->read_at ? 'bold' : '')
                    ->color(fn(Message $record): string => !$record->read_at ? 'primary' : '')
//                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject')
                    ->label('Subject')
                    ->translateLabel()
                    ->color(fn(Message $record): string => !$record->read_at ? 'primary' : '')
                    ->weight(fn(Message $record): string => !$record->read_at ? 'bold' : ''),
//                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Time')
                    ->translateLabel()
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(
                fn(Message $record): string => ReadMail::getUrl(['slug' => $record->slug])
            )
            ->filters([

            ])
            ->actions([
            ])
            ->bulkActions([
                // ...
            ])
            ->poll('15s')
            ->emptyStateHeading(__('No mail yet!'))
            ->emptyStateDescription(__('Please send mail to your empty email to see here.'))
            ->emptyStateIcon('heroicon-o-envelope-open');
    }

    public function selectMail(Mail $mail): void
    {
        $this->selectedMail = $mail;
        $this->setCurrentMail($mail);
        $this->mount();
    }

    public function removeMail(): void
    {
        if($this->selectedMail){
            $this->detachMail($this->selectedMail);
            $this->success('Mail removed successfully');
            $this->mount();
        }
    }

    /**
     */
    public function saveCustomMail()
    {
        try {
            $this->validate();
            $this->errorsList = [];
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorsList = $e->validator->errors()->all();
        }

        if ($this->errorsList) {
            foreach ($this->errorsList as $error) {
                return $this->error($error);
            }
        }

        try {
            $mail = $this->newCustomMail($this->customMail, $this->domain);
            $this->selectMail($mail);
            $this->success('Custom mail created successfully');
        } catch (DomainNotFoundException|Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function createRandom(): void
    {
        $mail = $this->newRandomMail();
        $this->selectMail($mail);
        $this->success('Random mail created successfully');
    }

    public function mount(): void
    {
        $this->selectedMail = $this->getCurrentMail();
        $this->domains = Domain::accessible()->get();
        $this->domain = $this->domains->first()->name;
        $this->mails = $this->allMails()->get();
        $this->countMail = $this->allMails()->count();
    }

    public function render()
    {

        return view('livewire.temp-mail.home')
            ->layoutData(['panel' => Filament::getPanel('frontend')]);
        //            ->layout('filament::components.layouts.app');
    }
}
