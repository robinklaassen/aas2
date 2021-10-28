<?php

declare(strict_types=1);

namespace App\Mail\internal;

use App\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class NewDeclaration extends Mailable
{
    use Queueable;

    use SerializesModels;

    public $member;

    public $formFilePath;

    public $inputData;

    public $totalAmount;

    public $fileNames;

    public function __construct(
        Member $member,
        $formFilePath,
        $inputData,
        $totalAmount,
        $filePaths
    ) {
        $this->member = $member;
        $this->formFilePath = $formFilePath;
        $this->inputData = $inputData;
        $this->totalAmount = $totalAmount;
        $this->filePaths = $filePaths;
    }

    public function build()
    {
        $this->attach($this->formFilePath);

        foreach ($this->filePaths as $filePath) {
            $this->attach($filePath);
        }

        $subject = sprintf('%s Nieuwe declaratie', Config::get('mail.subject_prefix.internal'));

        return $this->view('emails.internal.newDeclaration')
            ->subject($subject)
            ->cc([$this->member->getAnderwijsEmail()])
            ->to([Config::get('mail.addresses.penningmeester')])
            ->from([$this->member->getAnderwijsEmail()]);
    }
}
