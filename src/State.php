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
                'id'           => 1,
                'value'        => 'Alabama',
                'abbreviation' => 'AL',
            ],
            [
                'id'           => 2,
                'value'        => 'Alaska',
                'abbreviation' => 'AK',
            ],
            [
                'id'           => 3,
                'value'        => 'American Samoa',
                'abbreviation' => 'AS',
            ],
            [
                'id'           => 4,
                'value'        => 'Arizona',
                'abbreviation' => 'AZ',
            ],
            [
                'id'           => 5,
                'value'        => 'Arkansas',
                'abbreviation' => 'AR',
            ],
            [
                'id'           => 6,
                'value'        => 'California',
                'abbreviation' => 'CA',
            ],
            [
                'id'           => 7,
                'value'        => 'Colorado',
                'abbreviation' => 'CO',
            ],
            [
                'id'           => 8,
                'value'        => 'Connecticut',
                'abbreviation' => 'CT',
            ],
            [
                'id'           => 9,
                'value'        => 'Delaware',
                'abbreviation' => 'DE',
            ],
            [
                'id'           => 10,
                'value'        => 'District Of Columbia',
                'abbreviation' => 'DC',
            ],
            [
                'id'           => 11,
                'value'        => 'Federated States Of Micronesia',
                'abbreviation' => 'FM',
            ],
            [
                'id'           => 12,
                'value'        => 'Florida',
                'abbreviation' => 'FL',
            ],
            [
                'id'           => 13,
                'value'        => 'Georgia',
                'abbreviation' => 'GA',
            ],
            [
                'id'           => 14,
                'value'        => 'Guam',
                'abbreviation' => 'GU',
            ],
            [
                'id'           => 15,
                'value'        => 'Hawaii',
                'abbreviation' => 'HI',
            ],
            [
                'id'           => 16,
                'value'        => 'Idaho',
                'abbreviation' => 'ID',
            ],
            [
                'id'           => 17,
                'value'        => 'Illinois',
                'abbreviation' => 'IL',
            ],
            [
                'id'           => 18,
                'value'        => 'Indiana',
                'abbreviation' => 'IN',
            ],
            [
                'id'           => 19,
                'value'        => 'Iowa',
                'abbreviation' => 'IA',
            ],
            [
                'id'           => 20,
                'value'        => 'Kansas',
                'abbreviation' => 'KS',
            ],
            [
                'id'           => 21,
                'value'        => 'Kentucky',
                'abbreviation' => 'KY',
            ],
            [
                'id'           => 22,
                'value'        => 'Louisiana',
                'abbreviation' => 'LA',
            ],
            [
                'id'           => 23,
                'value'        => 'Maine',
                'abbreviation' => 'ME',
            ],
            [
                'id'           => 24,
                'value'        => 'Marshall Islands',
                'abbreviation' => 'MH',
            ],
            [
                'id'           => 25,
                'value'        => 'Maryland',
                'abbreviation' => 'MD',
            ],
            [
                'id'           => 26,
                'value'        => 'Massachusetts',
                'abbreviation' => 'MA',
            ],
            [
                'id'           => 27,
                'value'        => 'Michigan',
                'abbreviation' => 'MI',
            ],
            [
                'id'           => 28,
                'value'        => 'Minnesota',
                'abbreviation' => 'MN',
            ],
            [
                'id'           => 29,
                'value'        => 'Mississippi',
                'abbreviation' => 'MS',
            ],
            [
                'id'           => 30,
                'value'        => 'Missouri',
                'abbreviation' => 'MO',
            ],
            [
                'id'           => 31,
                'value'        => 'Montana',
                'abbreviation' => 'MT',
            ],
            [
                'id'           => 32,
                'value'        => 'Nebraska',
                'abbreviation' => 'NE',
            ],
            [
                'id'           => 33,
                'value'        => 'Nevada',
                'abbreviation' => 'NV',
            ],
            [
                'id'           => 34,
                'value'        => 'New Hampshire',
                'abbreviation' => 'NH',
            ],
            [
                'id'           => 35,
                'value'        => 'New Jersey',
                'abbreviation' => 'NJ',
            ],
            [
                'id'           => 36,
                'value'        => 'New Mexico',
                'abbreviation' => 'NM',
            ],
            [
                'id'           => 37,
                'value'        => 'New York',
                'abbreviation' => 'NY',
            ],
            [
                'id'           => 38,
                'value'        => 'North Carolina',
                'abbreviation' => 'NC',
            ],
            [
                'id'           => 39,
                'value'        => 'North Dakota',
                'abbreviation' => 'ND',
            ],
            [
                'id'           => 40,
                'value'        => 'Northern Mariana Islands',
                'abbreviation' => 'MP',
            ],
            [
                'id'           => 41,
                'value'        => 'Ohio',
                'abbreviation' => 'OH',
            ],
            [
                'id'           => 42,
                'value'        => 'Oklahoma',
                'abbreviation' => 'OK',
            ],
            [
                'id'           => 43,
                'value'        => 'Oregon',
                'abbreviation' => 'OR',
            ],
            [
                'id'           => 44,
                'value'        => 'Palau',
                'abbreviation' => 'PW',
            ],
            [
                'id'           => 45,
                'value'        => 'Pennsylvania',
                'abbreviation' => 'PA',
            ],
            [
                'id'           => 46,
                'value'        => 'Puerto Rico',
                'abbreviation' => 'PR',
            ],
            [
                'id'           => 47,
                'value'        => 'Rhode Island',
                'abbreviation' => 'RI',
            ],
            [
                'id'           => 48,
                'value'        => 'South Carolina',
                'abbreviation' => 'SC',
            ],
            [
                'id'           => 49,
                'value'        => 'South Dakota',
                'abbreviation' => 'SD',
            ],
            [
                'id'           => 50,
                'value'        => 'Tennessee',
                'abbreviation' => 'TN',
            ],
            [
                'id'           => 51,
                'value'        => 'Texas',
                'abbreviation' => 'TX',
            ],
            [
                'id'           => 52,
                'value'        => 'Utah',
                'abbreviation' => 'UT',
            ],
            [
                'id'           => 53,
                'value'        => 'Vermont',
                'abbreviation' => 'VT',
            ],
            [
                'id'           => 54,
                'value'        => 'Virgin Islands',
                'abbreviation' => 'VI',
            ],
            [
                'id'           => 55,
                'value'        => 'Virginia',
                'abbreviation' => 'VA',
            ],
            [
                'id'           => 56,
                'value'        => 'Washington',
                'abbreviation' => 'WA',
            ],
            [
                'id'           => 57,
                'value'        => 'West Virginia',
                'abbreviation' => 'WV',
            ],
            [
                'id'           => 58,
                'value'        => 'Wisconsin',
                'abbreviation' => 'WI',
            ],
            [
                'id'           => 59,
                'value'        => 'Wyoming',
                'abbreviation' => 'WY',
            ],
        ];
    }
}
