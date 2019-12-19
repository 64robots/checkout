<?php
namespace R64\Checkout\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use AWS;

class ToolKit
{
    public static function token(int $length = 64)
    {
        return bin2hex(random_bytes($length));
    }

    /***************************************************************************************
     ** FILE MANAGEMENT
     ***************************************************************************************/

    public static function getPresignedUrl(string $key)
    {
        $s3 = AWS::createClient('s3');

        $cmd = $s3->getCommand('GetObject', [
            'Bucket' => env('AWS_BUCKET'),
            'Key'    => $key
        ]);

        $request = $s3->createPresignedRequest($cmd, '+20 minutes');

        return (string) $request->getUri();
    }

    public static function duplicateS3File(string $source_key, string $destination_folder)
    {
        // generate new file's key
        $new_key = $destination_folder . '/' . Str::random(40);

        $s3 = AWS::createClient('s3');
        $s3->copyObject([
            'Bucket'     => env('AWS_BUCKET'),
            'Key'        => $new_key,
            'CopySource' => env('AWS_BUCKET') . '/' . $source_key,
        ]);
        return $new_key;
    }

    public static function getCopyName(string $original)
    {
        // check if a string ends with a number in parentheses
        $matches = [];
        if (!preg_match('#(\(\d+\))$#', $original, $matches)) {
            return $original . ' (1)';
        }

        // try to get the number and increment it
        $number = 0;
        $number_matches = [];
        if (preg_match('#(\d+)#', $matches[1], $number_matches)) {
            $number = $number_matches[1];
            $number = (int) $number;
            $number++;
        }

        // strip out the old number and replace with new in parentheses
        $new_string = Str::lreplace($matches[1], '', $original);
        $new_string = $new_string . '(' . $number . ')';
        return $new_string;
    }

    public static function getSantizedName(UploadedFile $file, bool $titleCase = false)
    {
        // get original name w/o extension
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // remove anything but Letters, Numbers, and Dashes
        $name = preg_replace('/[^0-9a-zA-Z\-\s]/', ' ', $name);

        // uppercase first letter, remove dashes
        if ($titleCase) {
            $name = Str::replace('-', ' ', $name);
            $name = title_case($name);
        }
        return $name;
    }

    public static function uniqueCode(int $length = 8, bool $all_caps = false, string $check_table = null, string $check_column = null)
    {
        // 1. generate code
        $code = self::generateCode($length, $all_caps);

        // 2. validate unique
        if ($check_table && $check_column) {
            $validator = Validator::make(['code' => $code], [
                'code' => 'unique:' . $check_table . ',' . $check_column]
            );
            // if fails run again
            if ($validator->fails()) {
                return self::uniqueCode($length, $all_caps, $check_table, $check_column);
            }
        }
        return $code;
    }

    public static function generateCode(int $length = 8, bool $all_caps = false)
    {
        // set characters
        $pool = array_merge(range(0,9), range('A', 'Z'));
        if (!$all_caps) {
            $pool = range('a', 'z');
        }

        // generate code
        $code = '';
        for($i=0; $i < $length; $i++) {
            $code .= $pool[mt_rand(0, count($pool) - 1)];
        }
        return $code;
    }

    public static function uniqueNumber(int $length = 8, string $check_table = null, string $check_column = null)
    {
        // 1. unique number
        $number = randomNumber($length);

        // 2. validate unique
        if ($check_table && $check_column) {
            $validator = Validator::make(['code' => $number], [
                'code' => 'unique:' . $check_table . ',' . $check_column]
            );
            // if fails run again
            if ($validator->fails()) {
                return self::uniqueNumber($length, $check_table, $check_column);
            }
        }
        return $number;
    }

    /***************************************************************************************
     ** FORMS
     ***************************************************************************************/

    public static function formatPhoneNumber(string $phone)
    {
        return "(" . substr($phone, 0, 3) . ") " . substr($phone, 3, 3) . "-" . substr($phone, 6);
    }

    public static function states()
    {
        return [
            'AL'=>'Alabama',
            'AK'=>'Alaska',
            'AZ'=>'Arizona',
            'AR'=>'Arkansas',
            'CA'=>'California',
            'CO'=>'Colorado',
            'CT'=>'Connecticut',
            'DE'=>'Delaware',
            'DC'=>'District of Columbia',
            'FL'=>'Florida',
            'GA'=>'Georgia',
            'HI'=>'Hawaii',
            'ID'=>'Idaho',
            'IL'=>'Illinois',
            'IN'=>'Indiana',
            'IA'=>'Iowa',
            'KS'=>'Kansas',
            'KY'=>'Kentucky',
            'LA'=>'Louisiana',
            'ME'=>'Maine',
            'MD'=>'Maryland',
            'MA'=>'Massachusetts',
            'MI'=>'Michigan',
            'MN'=>'Minnesota',
            'MS'=>'Mississippi',
            'MO'=>'Missouri',
            'MT'=>'Montana',
            'NE'=>'Nebraska',
            'NV'=>'Nevada',
            'NH'=>'New Hampshire',
            'NJ'=>'New Jersey',
            'NM'=>'New Mexico',
            'NY'=>'New York',
            'NC'=>'North Carolina',
            'ND'=>'North Dakota',
            'OH'=>'Ohio',
            'OK'=>'Oklahoma',
            'OR'=>'Oregon',
            'PA'=>'Pennsylvania',
            'RI'=>'Rhode Island',
            'SC'=>'South Carolina',
            'SD'=>'South Dakota',
            'TN'=>'Tennessee',
            'TX'=>'Texas',
            'UT'=>'Utah',
            'VT'=>'Vermont',
            'VA'=>'Virginia',
            'WA'=>'Washington',
            'WV'=>'West Virginia',
            'WI'=>'Wisconsin',
            'WY'=>'Wyoming',
        ];
    }
}
