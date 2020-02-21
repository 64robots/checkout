<?php

namespace App\Nova;

use Illuminate\Http\Request;

use Laravel\Nova\Fields\Password;
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

class Customer extends Resource
{
    /***************************************************************************************
     ** ATTRIBUTE MODS
     ***************************************************************************************/
    
    public static $model = \R64\Checkout\Models\Customer::class;
    public static $title = 'id';
    public static $group = 'Order Management';
    public static $search = [
        'id', 'first_name', 'last_name', 'email',
    ];
    public static $displayInNavigation = true;

    /***************************************************************************************
     ** OVERRIDES
     ***************************************************************************************/

    public static function label()
    {
        return 'Customers';
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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('First Name', 'first_name')
            ->sortable()
            ->rules('required', 'max:255'),

            Text::make('Last Name', 'last_name')
            ->sortable()
            ->rules('required', 'max:255'),

            Text::make('Email', 'email')
            ->sortable()
            ->rules('required', 'email', 'max:254')
            ->creationRules('unique:users,email')
            ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password', 'password')
            ->onlyOnForms()
            ->creationRules('required', 'string', 'min:8')
            ->updateRules('nullable', 'string', 'min:8'),

            Text::make('Phone', 'phone')
            ->rules('required', 'max:255'),
                
                DateTime::make('Created At'),
            ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    public function title()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /***************************************************************************************
     ** HELPERS
     ***************************************************************************************/

}
