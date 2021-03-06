<?php

namespace App\Nova;

use Illuminate\Http\Request;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

use Laravel\Nova\Http\Requests\NovaRequest;

class Cart extends Resource
{
    /***************************************************************************************
     ** ATTRIBUTE MODS
     ***************************************************************************************/

    public static $model = \R64\Checkout\Models\Cart::class;
    public static $title = 'id';
    public static $search = [
        'id',
    ];
    public static $displayInNavigation = false;

    /***************************************************************************************
     ** OVERRIDES
     ***************************************************************************************/

    public static function label()
    {
        return 'Carts';
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /***************************************************************************************
     ** GENERAL
     ***************************************************************************************/

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Customer', 'customer', \App\Nova\Customer::class),

            BelongsTo::make('Coupon', 'coupon', \App\Nova\Coupon::class),

            HasMany::make('Cart Items', 'cartItems', \App\Nova\CartItem::class),

            Text::make('Shipping First Name', 'shipping_first_name'),
            Text::make('Shipping Last Name', 'shipping_last_name'),
            Text::make('Shipping Address Line1', 'shipping_address_line1'),
            Text::make('Shipping Address Line2', 'shipping_address_line2'),
            Text::make('Shipping Address City', 'shipping_address_city'),
            Text::make('Shipping Address Region', 'shipping_address_region'),
            Text::make('Shipping Address Zipcode', 'shipping_address_zipcode'),
            Text::make('Shipping Address Phone', 'shipping_address_phone'),
            Text::make('Billing First Name', 'billing_first_name'),
            Text::make('Billing Last Name', 'billing_last_name'),
            Text::make('Billing Address Line1', 'billing_address_line1'),
            Text::make('Billing Address Line2', 'billing_address_line2'),
            Text::make('Billing Address City', 'billing_address_city'),
            Text::make('Billing Address Region', 'billing_address_region'),
            Text::make('Billing Address Zipcode', 'billing_address_zipcode'),
            Text::make('Billing Address Phone', 'billing_address_phone'),

            Text::make('Token', 'token'),
            DateTime::make('Created At'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /***************************************************************************************
     ** HELPERS
     ***************************************************************************************/

}
