<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Password broker
     */
    protected $broker = 'teachers';
    /**
     * Password reset email view
     */
    protected $linkRequestView = 'admin.passwords.email';

    /**
     * Password reset view
     */
    protected $resetView = 'admin.passwords.reset';

    /**
     * Reset email subject
     */
    protected $subject = '密码重置链接';

    /**
     * Redirect after reset
     */
     protected $redirectTo = '/admin';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin,/admin');
    }
}
