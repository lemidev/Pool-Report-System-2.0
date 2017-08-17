<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Client;
use App\User;
use App\PRS\Helpers\NotificationHelpers;
use Carbon\Carbon;
use Storage;
use App\UserRoleCompany;

class NewClientMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $client;
    protected $userRoleCompany;
    protected $helper;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Client $client, UserRoleCompany $userRoleCompany, NotificationHelpers $helper)
    {
        $this->client = $client;
        $this->userRoleCompany = $userRoleCompany;
        $this->helper = $helper;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $client = $this->client;
        $loginSigner = $this->userRoleCompany->urlSigners()->create([
            'token' => str_random(128),
            'expire' => Carbon::now()->addDays(3)
        ]);
        $unsubscribeSigner = $this->userRoleCompany->urlSigners()->create([
            'token' => str_random(128),
            'expire' => Carbon::now()->addDays(10)
        ]);
        $person =  $this->helper->personStyled($this->userRoleCompany);
        $location = "clients/{$client->seq_id}";

        $image = Storage::url('images/assets/email/new_user.png');
        if($this->client->imageExists()){
            $image = Storage::url($this->client->normalImage(1));
        }

        $data = [
                    'logo' => Storage::url('images/assets/app/logo-2.png'),
                    'objectImage' => $image,
                    'title' => "New Client Created!",
                    'moreInfo' => "The client {$client->name} {$client->last_name} was created by {$person}",
                    'magicLink' => url("/signin/{$loginSigner->token}?location={$location}"),
                    'unsubscribeLink' => url('/unsubscribe').'/'.$unsubscribeSigner->token,
                ];

        return $this->subject('New Client Created')
                    ->view('emails.newObject')
                    ->with($data);
    }
}
