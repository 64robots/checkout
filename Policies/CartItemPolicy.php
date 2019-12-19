<?php

namespace App\Policies;

use R64\Checkout\Models\CartItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the cartItem.
     *
     * @param  \App\Models\User     $user
     * @param  \App\Models\CartItem $cartItem
     * @return mixed
     */
    public function view(User $user, CartItem $cartItem)
    {
        return true;
    }

    /**
     * Determine whether the user can create cartItems.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return true;
    }

    /**
     * Determine whether the user can update the cartItem.
     *
     * @param  \App\Models\User     $user
     * @param  \App\Models\CartItem $cartItem
     * @return mixed
     */
    public function update(User $user, CartItem $cartItem)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the cartItem.
     *
     * @param  \App\Models\User     $user
     * @param  \App\Models\CartItem $cartItem
     * @return mixed
     */
    public function delete(User $user, CartItem $cartItem)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the cartItem.
     *
     * @param  \App\Models\User     $user
     * @param  \App\Models\CartItem $cartItem
     * @return mixed
     */
    public function restore(User $user, CartItem $cartItem)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the cartItem.
     *
     * @param  \App\Models\User     $user
     * @param  \App\Models\CartItem $cartItem
     * @return mixed
     */
    public function forceDelete(User $user, CartItem $cartItem)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }
}
