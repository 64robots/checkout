<?php
namespace R64\Checkout\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Log;

class Search
{
    public $term;
    public $order_field;
    public $order_direction;
    public $limit;

    public $paginate;
    public $items_per_page;
    public $params;

    // conditional params
    public $user;

    public function __construct(User $user, string $term = null, array $additional_params = [])
    {
        // Searching
        $this->term = $term;

        // Ordering
        $this->order_field = Arr::get($additional_params, 'order_field');
        $this->order_direction = Arr::get($additional_params, 'order_direction', 'ASC');

        // Limiting (Pagination Off)
        $this->limit = Arr::get($additional_params, 'limit', 0);

        // Pagination
        $this->paginate = Arr::get($additional_params, 'paginate', false);
        $this->items_per_page = Arr::get($additional_params, 'items_per_page', 10);

        // additional params
        $this->params = $additional_params;
        $this->user = $user;
    }

    /***************************************************************************************
     ** USERS
     ***************************************************************************************/

    public function users()
    {
        $users = User::when($this->term, function ($query) {
            $query->whereRaw($this->getRawConcatStatement($this->term))
                                   ->orWhere('email', 'LIKE', '%' . $this->term . '%');
        })
                        ->when($this->order_field, function ($query) {
                            $query->orderBy($this->order_field, $this->order_direction);
                        })
                        ->when(!$this->paginate && $this->limit, function ($query) {
                            $query->take($this->limit);
                        })
                        ->select('users.*');
        // pagination
        if ($this->paginate && $this->items_per_page) {
            return $users->paginate($this->items_per_page);
        }
        return $users->get();
    }

    /***************************************************************************************
     ** HELPERS
     ***************************************************************************************/

    protected function getRawConcatStatement($term)
    {
        if (app()->environment('testing')) {
            return '(`users`.`first_name` || `users`.`last_name`) LIKE "%' . $term . '%"';
        }
        return 'CONCAT(`users`.`first_name`, " ", `users`.`last_name`) LIKE "%' . $term . '%"';
    }
}
