<?php

namespace App\Policies;

use App\Attachment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function show_attachment(User $user, Attachment $attachment)
    {
        return false;
    }

    public function show_avatar(User $user)
    {
        return true;
    }
}
