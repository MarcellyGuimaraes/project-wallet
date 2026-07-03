<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Completed = 'completed';
    case Reversed = 'reversed';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Completed => 'Concluída',
            self::Reversed => 'Revertida',
            self::Failed => 'Falhou',
        };
    }
}
