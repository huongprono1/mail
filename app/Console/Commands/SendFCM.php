<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use ZBateson\MailMimeParser\IMessage;
use ZBateson\MailMimeParser\Message;

class SendFCM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-fcm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //        $user = User::first();
        //        $message = Message::with('email')->whereSlug('0196ec82-7bcf-7fb8-a9d8-4b6c3eb6d328')->first();
        //        if (!$message) {
        //            $this->error('Message not found');
        //
        //            return;
        //        }
        //        event(new NewMessageEvent($message->email, $message));
        //        $user->sendPasswordResetNotification(token: '1234567890abcdefg');
        //        //        $user->notify(new NewEmailMessage($message));

        //        echo Storage::disk('local')->get('sample.eml');
        $info = $this->extractEmailInfo(Storage::get('sample_forward.eml'));

        print_r($info);
    }

    public function extractEmailInfo($source): array
    {
        // 1. Parse
        $msg = ($source instanceof IMessage)
            ? $source
            : Message::from($source, /* attach handle */ true);   // tiện lợi hơn MailMimeParser::parse() :contentReference[oaicite:1]{index=1}

        // 2. Header thô & đã chuẩn hoá
        $headersRaw = $msg->getRawHeaders();
        $headersNorm = [];
        foreach ($msg->getAllHeaders() as $h) {
            $headersNorm[$h->getName()] = $h->getValue(); // tự giải mã RFC2047, gộp folded-line
        }

        // 3. Địa chỉ
        $addr = fn (string $name) => array_map(
            fn ($a) => ['name' => $a?->getPersonName(), 'email' => $a?->getEmail()],
            $msg->getHeader($name)?->getAddresses() ?? []
        );

        // 4. Nội dung
        $text = $msg->getTextContent(); // ưu tiên part text/plain
        $html = $msg->getHtmlContent(); // ưu tiên part text/html

        // 5. Đính kèm & inline (Content-ID)
        $attachments = [];
        foreach ($msg->getAllAttachmentParts() as $i => $part) {
            $path = Storage::path("{$part->getFilename()}");
            $attachments[] = [
                'filename' => $part->getFilename(),
                'mime' => $part->getContentType(),
                //                'size'     => $part->getSize(),
                'cid' => $part->getContentId(),
                'saved_to' => $path,
            ];
            $part->saveContent($path);
        }

        // 7. Tổng hợp
        return [
            'message_id' => $msg->getHeaderValue('Message-ID'),
            'subject' => $msg->getHeaderValue('Subject'),
            'date' => $msg->getHeader('Date')?->getDateTime()?->format(DATE_ATOM),
            'from' => $msg->getHeader('From')?->getValue(),
            'to' => $msg->getHeader('To')?->getValue(),
            'cc' => $msg->getHeader('Cc')?->getValue(),
            'bcc' => $msg->getHeader('Bcc')?->getValue(),
            'reply_to' => $msg->getHeader('Reply-To')?->getValue(),
            'in_reply_to' => $msg->getHeaderValue('In-Reply-To'),
            'references' => $msg->getHeaderValue('References'),
            'return_path' => $msg->getHeaderValue('Return-Path'),
            'priority' => $msg->getHeaderValue('X-Priority') ?? $msg->getHeaderValue('Priority'),
            'dkim_sig' => $msg->getHeaderValue('DKIM-Signature'),
            'spf' => $msg->getHeaderValue('Received-SPF'),
            'mime_version' => $msg->getHeaderValue('MIME-Version'),
            'content_type' => $msg->getHeaderValue('Content-Type'),
            //            'text'         => $text,
            //            'html'         => $html,
            'attachments' => $attachments,
            //            'headers_raw'  => $headersRaw,
            //            'headers'      => $headersNorm,
        ];
    }
}
