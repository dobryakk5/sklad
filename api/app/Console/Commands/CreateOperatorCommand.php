<?php

namespace App\Console\Commands;

use App\Models\Operator;
use Illuminate\Console\Command;

final class CreateOperatorCommand extends Command
{
    protected $signature = 'operator:create {email} {name} {password} {--role=admin}';
    protected $description = 'Создать оператора админки';

    public function handle(): int
    {
        $role = (string) $this->option('role');

        if (! in_array($role, ['admin', 'operator'], true)) {
            $this->error('Роль должна быть admin или operator.');

            return self::FAILURE;
        }

        if (Operator::query()->where('email', $this->argument('email'))->exists()) {
            $this->error('Оператор с таким email уже существует.');

            return self::FAILURE;
        }

        Operator::query()->create([
            'email' => $this->argument('email'),
            'name' => $this->argument('name'),
            'password' => $this->argument('password'),
            'role' => $role,
        ]);

        $this->info("Оператор {$this->argument('email')} создан с ролью {$role}.");

        return self::SUCCESS;
    }
}
