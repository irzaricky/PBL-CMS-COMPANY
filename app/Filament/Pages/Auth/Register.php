<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;
use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;

class Register extends BaseRegister
{
    protected function handleRegistration(array $data): Model
    {
        $data['status_kepegawaian'] = 'Non Pegawai';

        return $this->getUserModel()::create($data);
    }
    use HasCustomLayout;
}
