<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Variant;
use Illuminate\Auth\Access\Response;

class VariantPolicy
{
    public function viewAny(User $user)
    {
        // semua bisa melihat menu (customer, staff, admin)
        return true;
    }

    public function create(User $user)
    {
        // semua staff dan admin bisa membuat menu
        return true;
    }

    public function update(User $user)
    {
        // semua staff dan admin bisa mengupdate menu
        return true;
    }

    public function delete(User $user)
    {
        // hanya admin yang bisa menghapus menu
        return $user->hasRole('admin');
    }

    public function restore(User $user, Menu $menu): bool
    {
        // hanya admin yang bisa merestore menu
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Menu $menu): bool
    {
        // hanya admin yang bisa menghapus permanen menu
        return $user->hasRole('admin');
    }
}
