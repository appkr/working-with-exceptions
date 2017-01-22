<?php

namespace App;

/**
 * Class GuestUser
 * @package App
 */
class GuestUser extends User
{
    public $id = null;

    public $name = 'Guest';

    public $email = 'null@null.com';

    /** {@inheritdoc} */
    public function can($ability, $arguments = []) {
        return false;
    }

    /** {@inheritdoc} */
    public function sendPasswordResetNotification($token)
    {
        return null;
    }
}
