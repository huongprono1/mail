<?php

use eXorus\PhpMimeMailParser\Parser;

test('test mail parse working', function () {
    // Đường dẫn tới file test EML
    $filePath = base_path('tests/files/email.eml');

    // Kiểm tra file tồn tại
    expect(file_exists($filePath))->toBeTrue();

    // Khởi tạo Parser và đọc file
    //    $parser = new Parser();
    //    $parser->setPath($filePath);
    //
    //    $from = $parser->getHeader('from');
    //    $to = $parser->getHeader('to');
    //    $subject = $parser->getHeader('subject');
    //    $htmlBody = $parser->getMessageBody('html');

    $message = \ZBateson\MailMimeParser\Message::from(file_get_contents($filePath), true);
    $subject = $message->getSubject();
    $text = $message->getTextContent();
    $html = $message->getHtmlContent();
    $from = $message->getHeader('From');
    $fromName = $from->getPersonName();
    $fromEmail = $from->getEmail();

    $to = $message->getHeader('To');
    // first email address can be accessed directly
    $firstToName = $to->getPersonName();
    $firstToEmail = $to->getEmail();

    // Hiển thị nội dung khi chạy test
    dump([
        'From name' => $fromName,
        'from mail' => $fromEmail,
        'To name' => $firstToName,
        'To email' => $firstToEmail,
        'Subject' => $subject,
        //        'HTML Body' => $html,
        //        'Text Body' => $text,
    ]);

    // Kiểm tra nội dung không null hoặc rỗng
    expect($from)->not->toBeFalse('From is empty');
    expect($to)->not->toBeBool(false);
    expect($subject)->not->toBeBool(false);
    expect($html)->not->toBeFalse('Body is not empty');
});
