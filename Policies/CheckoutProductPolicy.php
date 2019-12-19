<?php

namespace App\Policies;

use R64\Checkout\Models\CheckoutProduct;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckoutProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the checkout product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckoutProduct $checkoutProduct
     * @return mixed
     */
    public function view(User $user, CheckoutProduct $checkoutProduct)
    {
        return true;
    }

    /**
     * Determine whether the user can create checkout products.
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
     * Determine whether the user can update the checkout product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckoutProduct $checkoutProduct
     * @return mixed
     */
    public function update(User $user, CheckoutProduct $checkoutProduct)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the checkout product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckoutProduct $checkoutProduct
     * @return mixed
     */
    public function delete(User $user, CheckoutProduct $checkoutProduct)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the checkout product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckoutProduct $checkoutProduct
     * @return mixed
     */
    public function restore(User $user, CheckoutProduct $checkoutProduct)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the checkout product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckoutProduct $checkoutProduct
     * @return mixed
     */
    public function forceDelete(User $user, CheckoutProduct $checkoutProduct)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }
}
