<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable {
  use Queueable, SerializesModels;

  public $template;
  
  public function __construct($template) {
    $this->template = $template;
  }
  
  public function build() {
    return $this->from(config('mail.from.address'), config('mail.from.name'))
      ->subject('[MOA] 인증 메일입니다')
      ->html($this->template);
  }
}