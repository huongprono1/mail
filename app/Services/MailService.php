<?php

namespace App\Services;

use App\Models\Mail;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use ZBateson\MailMimeParser\IMessage;
use ZBateson\MailMimeParser\MailMimeParser;

class MailService
{
    private IMessage $parser;

    private Mail $mail;

    private string $sender;

    private string $recipient;

    private string $subject;

    public function __construct(Mail $mail, string $rawMessage, $sender, $recipient)
    {
        // Initialize any dependencies if needed
        MailMimeParser::setGlobalLogger(Log::channel('mail-parser'));
        $this->parser = \ZBateson\MailMimeParser\Message::from($rawMessage, true);
        $this->mail = $mail;
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    public function storeMessage(): ?Message
    {
        $subject = $this->parser->getSubject();
        if (! $subject) {
            $subject = 'No Subject';
        }
        $this->subject = $subject;

        $text = $this->parser->getTextContent();
        $html = $this->parser->getHtmlContent();
        $from = $this->parser->getHeader('From');
        $fromName = $from->getPersonName() ?: 'Unknown Sender';
        $fromEmail = $from->getEmail();

        $to = $this->parser->getHeader('To');
        $firstToEmail = $to->getEmail();

        //        $attachments = [];
        //        foreach ($this->parser->getAllAttachmentParts() as $i => $part) {
        //            $attachments[] = [
        //                'filename' => $part->getFilename(),
        //                'mime'     => $part->getContentType(),
        //                'size'     => $part->getSize(),
        //                'cid'      => $part->getContentId(),
        //                // Hoặc lưu ra đĩa:
        //                // 'saved_to' => $part->saveContent("/tmp/{$part->getFilename()}")
        //            ];
        //        }

        //                $ccHeader = $this->parser->getHeader('Cc');
        //                $bccHeader = $this->parser->getHeader('Bcc');
        //                $ccAddresses  = $ccHeader ? array_map(fn($h) => $h->getEmailAddress(), $this->parser->getHeader('cc')->getAll()) : [];
        //                $bccAddresses = $bccHeader ? array_map(fn($h) => $h->getEmailAddress(), $this->parser->getHeader('bcc')->getAll()) : [];

        // Save the email details to the database
        $message = new Message;
        $message->email_id = $this->mail->id;
        $message->sender_name = $fromName;
        $message->from = $fromEmail;
        $message->original_from = $this->sender;
        $message->to = $firstToEmail;
        $message->original_to = $this->recipient;
        $message->subject = $subject;
        $message->body = $html ?? $text;
        $message->save();

        return $message;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }
}
