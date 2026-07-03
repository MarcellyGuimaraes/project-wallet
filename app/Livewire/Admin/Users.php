<?php

namespace App\Livewire\Admin;

use App\Exceptions\AdminActionNotAllowedException;
use App\Models\User;
use App\Services\Admin\UserBlockingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Users extends Component
{
    use WithPagination;

    public ?string $errorMessage = null;

    public ?string $successMessage = null;

    public function toggleBlock(int $userId, UserBlockingService $userBlockingService): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $target = User::findOrFail($userId);

        try {
            if ($target->isBlocked()) {
                $userBlockingService->unblock($target);
                $this->successMessage = "Conta de {$target->name} desbloqueada.";
            } else {
                $userBlockingService->block(Auth::user(), $target);
                $this->successMessage = "Conta de {$target->name} bloqueada.";
            }
        } catch (AdminActionNotAllowedException $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.admin.users', [
            'users' => User::where('is_admin', false)
                ->with('wallet')
                ->orderBy('name')
                ->paginate(10),
        ]);
    }
}
