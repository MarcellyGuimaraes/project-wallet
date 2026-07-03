<?php

namespace App\Services\Admin;

use App\Exceptions\AdminActionNotAllowedException;
use App\Models\User;

class UserBlockingService
{
    public function block(User $actor, User $target): void
    {
        if ($actor->is($target)) {
            throw new AdminActionNotAllowedException('Você não pode bloquear a própria conta.');
        }

        if ($target->isAdmin()) {
            throw new AdminActionNotAllowedException('Não é possível bloquear outro administrador.');
        }

        $target->forceFill(['blocked_at' => now()])->save();
    }

    public function unblock(User $target): void
    {
        $target->forceFill(['blocked_at' => null])->save();
    }
}
