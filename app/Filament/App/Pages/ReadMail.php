<?php

namespace App\Filament\App\Pages;

use App\Models\Message;
use App\Traits\HasMailable;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class ReadMail extends Page
{
    use HasMailable;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.read-mail';

    protected ?string $heading = '';

    protected ?string $pageTitle = 'Read Mail';

    protected static ?string $slug = 'message/{slug}';

    public Message $message;

    public ?string $mailBody;

    public function getTitle(): string|Htmlable
    {
        return $this->pageTitle;
    }

    /**
     * @throws InvalidSelectorException
     */
    public function mount($slug): void
    {
        $this->message = Message::query()->where('slug', $slug)->first() ?? abort(404);
        if (! $this->message->email?->isOwnedBy($this->getUserClient())) {
            abort(403);
        }
        $this->pageTitle = $this->message->subject ?? '';
        $message = $this->message;
        if (is_null($message->read_at)) {
            defer(function () use ($message) {
                $message->read_at = now();
                $message->save();
            });
        }
        //        $html = Purifier::clean($message->body);
        $html = Str::sanitizeHtml($message->body);
        $document = new Document($html);
        foreach ($document->find('a') as $link) {
            $link->setAttribute('target', '_blank');
        }
        $this->mailBody = e($document->html());
    }
}
