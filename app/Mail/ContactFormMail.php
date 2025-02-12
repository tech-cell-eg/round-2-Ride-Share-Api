<?php
// app/Mail/ContactFormMail.php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class ContactFormMail extends Mailable
{
    public $name;
    public $email;
    public $phone;
    public $message;

    public function __construct($name, $email, $phone, $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject('New Contact Form Submission')
                    ->view('Emails.contactform')
                    ->with([
                        'name' => $this->name,
                        'email' => $this->email,
                        'phone' => $this->phone,
                        'user_message' => (string) $this->message,
                    ]); 
    }
}
