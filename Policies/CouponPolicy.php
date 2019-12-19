<?php

namespace App\Policies;

use R64\Checkout\Models\Coupon;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CouponPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the coupon.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Coupon $coupon
     * @return mixed
     */
    public function view(User $user, Coupon $coupon)
    {
        return true;
    }

    /**
     * Determine whether the user can create coupons.
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
     * Determine whether the user can update the coupon.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Coupon $coupon
     * @return mixed
     */
    public function update(User $user, Coupon $coupon)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the coupon.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Coupon $coupon
     * @return mixed
     */
    public function delete(User $user, Coupon $coupon)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the coupon.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Coupon $coupon
     * @return mixed
     */
    public function restore(User $user, Coupon $coupon)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the coupon.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Coupon $coupon
     * @return mixed
     */
    public function forceDelete(User $user, Coupon $coupon)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }
}
