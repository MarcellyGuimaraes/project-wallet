<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Idempotente: pode rodar em toda subida do container sem duplicar
     * usuários, e sempre sincroniza a senha/flags do admin.
     */
    public function run(): void
    {
        if (! User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // firstOrNew + atribuição direta: contorna o mass-assignment
        // (is_admin não está no Fillable) e deixa o cast "hashed" cuidar da senha.
        $admin = User::firstOrNew(['email' => 'souzaguimaraesmarcelly@gmail.com']);
        $admin->name = 'Marcelly Guimarães';
        $admin->document = '11144477735';
        $admin->password = 'adm090806';
        $admin->is_admin = true;
        $admin->save();

        $admin->wallet()->firstOrCreate([], ['balance' => 0]);
    }
}
