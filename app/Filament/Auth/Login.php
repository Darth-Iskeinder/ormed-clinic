<?php
namespace App\Filament\Auth;

use Filament\Pages\Auth\Login as BaseAuth;
use Filament\Forms\Form;

class Login extends BaseAuth
{
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email' => $data['email'],
            'password'  => $data['password'],
            'active' => true,
        ];
    }
}
