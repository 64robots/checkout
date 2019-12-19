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

            BelongsTo::make('User', 'user', \App\Nova\Cart::class),

            Text::make('Token', 'token'),

            DateTime::make('Created At')->format('MM/DD h:mm a'),
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
