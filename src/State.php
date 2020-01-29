<?php

namespace R64\Checkout;

use Illuminate\Support\Arr;
use R64\Checkout\Contracts\State as StateContract;

class State implements StateContract
{
    public function all()
    {
        return [
            [
                'id' => 1,
                'value' => 'Alabama',
                'code' => 'AL',
            ],
            [
                'id' => 2,
                'value' => 'Alaska',
                'code' => 'AK',
            ],
            [
                'id' => 3,
                'value' => 'American Samoa',
                'code' => 'AS',
            ],
            [
                'id' => 4,
                'value' => 'Arizona',
                'code' => 'AZ',
            ],
            [
                'id' => 5,
                'value' => 'Arkansas',
                'code' => 'AR',
            ],
            [
                'id' => 6,
                'value' => 'California',
                'code' => 'CA',
            ],
            [
                'id' => 7,
                'value' => 'Colorado',
                'code' => 'CO',
            ],
            [
                'id' => 8,
                'value' => 'Connecticut',
                'code' => 'CT',
            ],
            [
                'id' => 9,
                'value' => 'Delaware',
                'code' => 'DE',
            ],
            [
                'id' => 10,
                'value' => 'District Of Columbia',
                'code' => 'DC',
            ],
            [
                'id' => 11,
                'value' => 'Federated States Of Micronesia',
                'code' => 'FM',
            ],
            [
                'id' => 12,
                'value' => 'Florida',
                'code' => 'FL',
            ],
            [
                'id' => 13,
                'value' => 'Georgia',
                'code' => 'GA',
            ],
            [
                'id' => 14,
                'value' => 'Guam',
                'code' => 'GU',
            ],
            [
                'id' => 15,
                'value' => 'Hawaii',
                'code' => 'HI',
            ],
            [
                'id' => 16,
                'value' => 'Idaho',
                'code' => 'ID',
            ],
            [
                'id' => 17,
                'value' => 'Illinois',
                'code' => 'IL',
            ],
            [
                'id' => 18,
                'value' => 'Indiana',
                'code' => 'IN',
            ],
            [
                'id' => 19,
                'value' => 'Iowa',
                'code' => 'IA',
            ],
            [
                'id' => 20,
                'value' => 'Kansas',
                'code' => 'KS',
            ],
            [
                'id' => 21,
                'value' => 'Kentucky',
                'code' => 'KY',
            ],
            [
                'id' => 22,
                'value' => 'Louisiana',
                'code' => 'LA',
            ],
            [
                'id' => 23,
                'value' => 'Maine',
                'code' => 'ME',
            ],
            [
                'id' => 24,
                'value' => 'Marshall Islands',
                'code' => 'MH',
            ],
            [
                'id' => 25,
                'value' => 'Maryland',
                'code' => 'MD',
            ],
            [
                'id' => 26,
                'value' => 'Massachusetts',
                'code' => 'MA',
            ],
            [
                'id' => 27,
                'value' => 'Michigan',
                'code' => 'MI',
            ],
            [
                'id' => 28,
                'value' => 'Minnesota',
                'code' => 'MN',
            ],
            [
                'id' => 29,
                'value' => 'Mississippi',
                'code' => 'MS',
            ],
            [
                'id' => 30,
                'value' => 'Missouri',
                'code' => 'MO',
            ],
            [
                'id' => 31,
                'value' => 'Montana',
                'code' => 'MT',
            ],
            [
                'id' => 32,
                'value' => 'Nebraska',
                'code' => 'NE',
            ],
            [
                'id' => 33,
                'value' => 'Nevada',
                'code' => 'NV',
            ],
            [
                'id' => 34,
                'value' => 'New Hampshire',
                'code' => 'NH',
            ],
            [
                'id' => 35,
                'value' => 'New Jersey',
                'code' => 'NJ',
            ],
            [
                'id' => 36,
                'value' => 'New Mexico',
                'code' => 'NM',
            ],
            [
                'id' => 37,
                'value' => 'New York',
                'code' => 'NY',
            ],
            [
                'id' => 38,
                'value' => 'North Carolina',
                'code' => 'NC',
            ],
            [
                'id' => 39,
                'value' => 'North Dakota',
                'code' => 'ND',
            ],
            [
                'id' => 40,
                'value' => 'Northern Mariana Islands',
                'code' => 'MP',
            ],
            [
                'id' => 41,
                'value' => 'Ohio',
                'code' => 'OH',
            ],
            [
                'id' => 42,
                'value' => 'Oklahoma',
                'code' => 'OK',
            ],
            [
                'id' => 43,
                'value' => 'Oregon',
                'code' => 'OR',
            ],
            [
                'id' => 44,
                'value' => 'Palau',
                'code' => 'PW',
            ],
            [
                'id' => 45,
                'value' => 'Pennsylvania',
                'code' => 'PA',
            ],
            [
                'id' => 46,
                'value' => 'Puerto Rico',
                'code' => 'PR',
            ],
            [
                'id' => 47,
                'value' => 'Rhode Island',
                'code' => 'RI',
            ],
            [
                'id' => 48,
                'value' => 'South Carolina',
                'code' => 'SC',
            ],
            [
                'id' => 49,
                'value' => 'South Dakota',
                'code' => 'SD',
            ],
            [
                'id' => 50,
                'value' => 'Tennessee',
                'code' => 'TN',
            ],
            [
                'id' => 51,
                'value' => 'Texas',
                'code' => 'TX',
            ],
            [
                'id' => 52,
                'value' => 'Utah',
                'code' => 'UT',
            ],
            [
                'id' => 53,
                'value' => 'Vermont',
                'code' => 'VT',
            ],
            [
                'id' => 54,
                'value' => 'Virgin Islands',
                'code' => 'VI',
            ],
            [
                'id' => 55,
                'value' => 'Virginia',
                'code' => 'VA',
            ],
            [
                'id' => 56,
                'value' => 'Washington',
                'code' => 'WA',
            ],
            [
                'id' => 57,
                'value' => 'West Virginia',
                'code' => 'WV',
            ],
            [
                'id' => 58,
                'value' => 'Wisconsin',
                'code' => 'WI',
            ],
            [
                'id' => 59,
                'value' => 'Wyoming',
                'code' => 'WY',
            ],
        ];
    }

    public function getByCode($stateCode)
    {
        return collect($this->all())->filter(function ($state) use ($stateCode) {
            return $state['code'] === $stateCode;
        })->first();
    }
}
