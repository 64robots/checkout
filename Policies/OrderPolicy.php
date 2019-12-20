<?php

namespace App\Policies;

use R64\Checkout\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        return true;
    }

    /**
     * Determine whether the user can create orders.
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
     * Determine whether the user can update the order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        if ($user->is_admin) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        if ($user->is_admin) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function restore(User $user, Order $order)
    {
        if ($user->is_admin) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function forceDelete(User $user, Order $order)
    {
        if ($user->is_admin) {
            return true;
        }

        return false;
    }
}
