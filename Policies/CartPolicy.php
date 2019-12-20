<?php

namespace App\Policies;

use R64\Checkout\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the cart.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Cart $cart
     * @return mixed
     */
    public function view(User $user, Cart $cart)
    {
        return true;
    }

    /**
     * Determine whether the user can create carts.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->is_admin) {
            return true;
        }

        return true;
    }

    /**
     * Determine whether the user can update the cart.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Cart $cart
     * @return mixed
     */
    public function update(User $user, Cart $cart)
    {
        if ($user->is_admin) {
            return true;
        }
        if ($user->id === $cart->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the cart.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Cart $cart
     * @return mixed
     */
    public function delete(User $user, Cart $cart)
    {
        if ($user->is_admin) {
            return true;
        }
        if ($user->id === $cart->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the cart.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Cart $cart
     * @return mixed
     */
    public function restore(User $user, Cart $cart)
    {
        if ($user->is_admin) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the cart.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Cart $cart
     * @return mixed
     */
    public function forceDelete(User $user, Cart $cart)
    {
        if ($user->is_admin) {
            return true;
        }

        return false;
    }
}
