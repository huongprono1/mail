<?php

namespace App\Livewire\TempMail;

use App\Exceptions\DomainNotFoundException;
use App\Models\Domain;
use App\Models\Mail;
use App\Traits\HasMailable;
use App\Traits\Toastable;
use Exception;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Home extends Component
{
    use HasMailable, Toastable;

    public $countMail = 0;

    #[Validate('required|exists:domains,name')]
    public $domain;

    #[Validate('required|min:3')]
    public $customMail;

    public $errorsList = []; // Để lưu trữ lỗi thủ công

    public $domains;

    public $selectedMail;

    public $mails;
    protected $listeners = [
        'reload' => '$refresh'
    ];

    public function selectMail(Mail $mail): void
    {
        foreach ($this->mails as $item) {
            if ($item->is($mail)) {
                $this->setCurrentMail($item);
                $this->selectedMail = $item;
                $this->dispatch('change-mail', mail: $mail);
                break;
            }
        }
        $this->mount();
    }

    public function removeMail(): void
    {
        if($this->selectedMail){
            $this->detachMail($this->selectedMail);
            $this->success('Mail removed successfully');
            $this->mount();
        }
        if($this->countMail > 0) {
            $this->dispatch('change-mail', mail: $this->getCurrentMail());
        }
    }

    /**
     */
    public function saveCustomMail(): null|bool
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
        $this->domains = Domain::accessible()->get();
        $this->domain = $this->domains->first()->name;
        $this->mails = $this->allMails()->get();
        $this->selectedMail = $this->getCurrentMail();
        $this->countMail = $this->allMails()->count();
    }

    public function render()
    {

        return view('livewire.temp-mail.home')
            ->layoutData(['panel' => Filament::getPanel('frontend')]);
        //            ->layout('filament::components.layouts.app');
    }
}
