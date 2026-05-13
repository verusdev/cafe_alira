<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isCook(): bool
    {
        return $this->role === 'cook';
    }

    public function isOrderTaker(): bool
    {
        return $this->role === 'order_taker';
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'order_taker' => 'Принимающий заявки',
            'cook' => 'Повар',
            'manager' => 'Руководитель',
            default => $this->role,
        };
    }

    public function canWrite(string $resource): bool
    {
        if ($this->isManager()) return true;

        return match ($resource) {
            'events' => $this->isOrderTaker(),
            'dishes', 'ingredients', 'refrigerators', 'inventory', 'purchases' => $this->isCook(),
            default => false,
        };
    }
}
