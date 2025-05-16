<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Countries;
use DB;
use Carbon\Carbon;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('countries')->insert([
            [
			    'code' => 'AD',
			    'name' => 'Andorra',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AE',
			    'name' => 'United Arab Emirates',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AF',
			    'name' => 'Afghanistan',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AG',
			    'name' => 'Antigua and Barbuda',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AI',
			    'name' => 'Anguilla',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AL',
			    'name' => 'Albania',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AM',
			    'name' => 'Armenia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AN',
			    'name' => 'Netherlands Antilles',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AO',
			    'name' => 'Angola',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AQ',
			    'name' => 'Antarctica',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AR',
			    'name' => 'Argentina',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AS',
			    'name' => 'American Samoa',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AT',
			    'name' => 'Austria',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AU',
			    'name' => 'Australia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AW',
			    'name' => 'Aruba',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'AZ',
			    'name' => 'Azerbaijan',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BA',
			    'name' => 'Bosnia and Herzegovina',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BB',
			    'name' => 'Barbados',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BD',
			    'name' => 'Bangladesh',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BE',
			    'name' => 'Belgium',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BF',
			    'name' => 'Burkina Faso',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BG',
			    'name' => 'Bulgaria',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BH',
			    'name' => 'Bahrain',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BI',
			    'name' => 'Burundi',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BJ',
			    'name' => 'Benin',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BM',
			    'name' => 'Bermuda',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BN',
			    'name' => 'Brunei Darussalam',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BO',
			    'name' => 'Bolivia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BR',
			    'name' => 'Brazil',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BS',
			    'name' => 'Bahamas',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BT',
			    'name' => 'Bhutan',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BV',
			    'name' => 'Bouvet Island',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BW',
			    'name' => 'Botswana',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BZ',
			    'name' => 'Belize',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'BY',
			    'name' => 'Belarus',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CA',
			    'name' => 'Canada',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CC',
			    'name' => 'Cocos  Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CD',
			    'name' => 'Congo',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CF',
			    'name' => 'Central African Republic',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CG',
			    'name' => 'Congo',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CH',
			    'name' => 'Switzerland',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CI',
			    'name' => "Cote D'Ivoire",
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CK',
			    'name' => 'Cook Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CL',
			    'name' => 'Chile',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CM',
			    'name' => 'Cameroon',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CN',
			    'name' => 'China',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CO',
			    'name' => 'Colombia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CR',
			    'name' => 'Costa Rica',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CS',
			    'name' => 'Serbia and Montenegro',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CU',
			    'name' => 'Cuba',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CV',
			    'name' => 'Cape Verde',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CX',
			    'name' => 'Christmas Island',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CY',
			    'name' => 'Cyprus',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'CZ',
			    'name' => 'Czech Republic',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'DE',
			    'name' => 'Germany',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'DJ',
			    'name' => 'Djibouti',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'DK',
			    'name' => 'Denmark',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'DM',
			    'name' => 'Dominica',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'DO',
			    'name' => 'Dominican Republic',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'DZ',
			    'name' => 'Algeria',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'EC',
			    'name' => 'Ecuador',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'EE',
			    'name' => 'Estonia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'EG',
			    'name' => 'Egypt',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'EH',
			    'name' => 'Western Sahara',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'ER',
			    'name' => 'Eritrea',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'ES',
			    'name' => 'Spain',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'ET',
			    'name' => 'Ethiopia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'FI',
			    'name' => 'Finland',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'FJ',
			    'name' => 'Fiji',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'FK',
			    'name' => 'Falkland Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'RS',
			    'name' => 'Serbia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'FM',
			    'name' => 'Micronesia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'FO',
			    'name' => 'Faeroe Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'FR',
			    'name' => 'France',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GA',
			    'name' => 'Gabon',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GB',
			    'name' => 'United Kingdom',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GD',
			    'name' => 'Grenada',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GE',
			    'name' => 'Georgia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GF',
			    'name' => 'French Guiana',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GH',
			    'name' => 'Ghana',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GI',
			    'name' => 'Gibraltar',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GL',
			    'name' => 'Greenland',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GM',
			    'name' => 'Gambia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GN',
			    'name' => 'Guinea',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GP',
			    'name' => 'Guadaloupe',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GQ',
			    'name' => 'Equatorial Guinea',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GR',
			    'name' => 'Greece',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GS',
			    'name' => 'South Georgia and the South Sandwich Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GT',
			    'name' => 'Guatemala',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GU',
			    'name' => 'Guam',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GW',
			    'name' => 'Guinea-Bissau',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'GY',
			    'name' => 'Guyana',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'HK',
			    'name' => 'Hong Kong',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'HM',
			    'name' => 'Heard and McDonald Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'HN',
			    'name' => 'Honduras',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'HR',
			    'name' => 'Croatia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'HT',
			    'name' => 'Haiti',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'HU',
			    'name' => 'Hungary',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'ID',
			    'name' => 'Indonesia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'IE',
			    'name' => 'Ireland',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'IL',
			    'name' => 'Israel',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'IN',
			    'name' => 'India',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'IO',
			    'name' => 'British Indian Ocean Territory',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'IQ',
			    'name' => 'Iraq',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'IR',
			    'name' => 'Iran',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'IS',
			    'name' => 'Iceland',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'IT',
			    'name' => 'Italy',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'JM',
			    'name' => 'Jamaica',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'JO',
			    'name' => 'Jordan',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'JP',
			    'name' => 'Japan',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'KE',
			    'name' => 'Kenya',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'KG',
			    'name' => 'Kyrgyz Republic',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'KH',
			    'name' => 'Cambodia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'KI',
			    'name' => 'Kiribati',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'KM',
			    'name' => 'Comoros',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'KN',
			    'name' => 'St. Kitts and Nevis',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'KP',
			    'name' => 'North Korea',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'KW',
			    'name' => 'Kuwait',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'KY',
			    'name' => 'Cayman Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'KZ',
			    'name' => 'Kazakhstan',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'LA',
			    'name' => "Lao People's Democratic Republic",
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'LB',
			    'name' => 'Lebanon',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'LC',
			    'name' => 'St. Lucia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'LI',
			    'name' => 'Liechtenstein',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'LK',
			    'name' => 'Sri Lanka',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'LR',
			    'name' => 'Liberia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'LS',
			    'name' => 'Lesotho',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'LT',
			    'name' => 'Lithuania',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'LU',
			    'name' => 'Luxembourg',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'LV',
			    'name' => 'Latvia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'LY',
			    'name' => 'Libyan Arab Jamahiriya',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MA',
			    'name' => 'Morocco',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MC',
			    'name' => 'Monaco',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MD',
			    'name' => 'Moldova',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MG',
			    'name' => 'Madagascar',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MH',
			    'name' => 'Marshall Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MK',
			    'name' => 'Macedonia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'ML',
			    'name' => 'Mali',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MM',
			    'name' => 'Myanmar',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MN',
			    'name' => 'Mongolia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MO',
			    'name' => 'Macao',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MP',
			    'name' => 'Northern Mariana Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MQ',
			    'name' => 'Martinique',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MR',
			    'name' => 'Mauritania',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MS',
			    'name' => 'Montserrat',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MT',
			    'name' => 'Malta',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MU',
			    'name' => 'Mauritius',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MV',
			    'name' => 'Maldives',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MW',
			    'name' => 'Malawi',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MX',
			    'name' => 'Mexico',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MY',
			    'name' => 'Malaysia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'MZ',
			    'name' => 'Mozambique',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'NA',
			    'name' => 'Namibia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'NC',
			    'name' => 'New Caledonia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'NE',
			    'name' => 'Niger',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'NF',
			    'name' => 'Norfolk Island',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'NG',
			    'name' => 'Nigeria',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'ME',
			    'name' => 'Montenegro',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'NI',
			    'name' => 'Nicaragua',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'NL',
			    'name' => 'Netherlands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'NO',
			    'name' => 'Norway',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'NP',
			    'name' => 'Nepal',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'NR',
			    'name' => 'Nauru',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'NU',
			    'name' => 'Niue',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'NZ',
			    'name' => 'New Zealand',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'OM',
			    'name' => 'Oman',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PA',
			    'name' => 'Panama',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PE',
			    'name' => 'Peru',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PF',
			    'name' => 'French Polynesia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PG',
			    'name' => 'Papua New Guinea',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PH',
			    'name' => 'Philippines',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'YU',
			    'name' => 'Yugoslavia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'XK',
			    'name' => 'Kosovo',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'XC',
			    'name' => 'Czechoslovakia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PK',
			    'name' => 'Pakistan',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PL',
			    'name' => 'Poland',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PM',
			    'name' => 'St. Pierre and Miquelon',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PN',
			    'name' => 'Pitcairn Island',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PR',
			    'name' => 'Puerto Rico',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PS',
			    'name' => 'Palestinian Territory',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PT',
			    'name' => 'Portugal',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PW',
			    'name' => 'Palau',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'PY',
			    'name' => 'Paraguay',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'QA',
			    'name' => 'Qatar',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'RE',
			    'name' => 'Reunion',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'RO',
			    'name' => 'Romania',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'RU',
			    'name' => 'Russia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'RW',
			    'name' => 'Rwanda',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SA',
			    'name' => 'Saudi Arabia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SB',
			    'name' => 'Solomon Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SC',
			    'name' => 'Seychelles',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SD',
			    'name' => 'Sudan',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SE',
			    'name' => 'Sweden',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SG',
			    'name' => 'Singapore',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SH',
			    'name' => 'St. Helena',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SI',
			    'name' => 'Slovenia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SJ',
			    'name' => 'Svalbard & Jan Mayen Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SK',
			    'name' => 'Slovakia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SL',
			    'name' => 'Sierra Leone',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SM',
			    'name' => 'San Marino',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SN',
			    'name' => 'Senegal',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SO',
			    'name' => 'Somalia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SR',
			    'name' => 'Suriname',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'ST',
			    'name' => 'Sao Tome and Principe',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SV',
			    'name' => 'El Salvador',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SY',
			    'name' => 'Syrian Arab Republic',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SZ',
			    'name' => 'Swaziland',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TC',
			    'name' => 'Turks and Caicos Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TD',
			    'name' => 'Chad',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TF',
			    'name' => 'French Southern Territories',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TG',
			    'name' => 'Togo',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TH',
			    'name' => 'Thailand',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TJ',
			    'name' => 'Tajikistan',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TK',
			    'name' => 'Tokelau',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TL',
			    'name' => 'Timor-Leste',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TM',
			    'name' => 'Turkmenistan',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TN',
			    'name' => 'Tunisia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TO',
			    'name' => 'Tonga',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TR',
			    'name' => 'Turkey',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TT',
			    'name' => 'Trinidad and Tobago',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TV',
			    'name' => 'Tuvalu',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TW',
			    'name' => 'Taiwan',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'TZ',
			    'name' => 'Tanzania',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'UA',
			    'name' => 'Ukraine',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'UG',
			    'name' => 'Uganda',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'UM',
			    'name' => 'United States Minor Outlying Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'US',
			    'name' => 'United States of America',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'UY',
			    'name' => 'Uruguay',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'UZ',
			    'name' => 'Uzbekistan',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'VA',
			    'name' => 'Holy See',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'VC',
			    'name' => 'St. Vincent and the Grenadines',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'VE',
			    'name' => 'Venezuela',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'VG',
			    'name' => 'British Virgin Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'VI',
			    'name' => 'US Virgin Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'VN',
			    'name' => 'Vietnam',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'VU',
			    'name' => 'Vanuatu',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'WF',
			    'name' => 'Wallis and Futuna Islands',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'WS',
			    'name' => 'Samoa',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'YE',
			    'name' => 'Yemen',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'YT',
			    'name' => 'Mayotte',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'ZA',
			    'name' => 'South Africa',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'ZM',
			    'name' => 'Zambia',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'ZW',
			    'name' => 'Zimbabwe',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'KR',
			    'name' => 'South Korea',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'XG',
			    'name' => 'East Germany',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SU',
			    'name' => 'Soviet Union',
			    'created_at' => Carbon::now(),
			],
			[
			    'code' => 'SS',
			    'name' => 'South Sudan',
				'created_at' => Carbon::now(),
			]
        ]);
    }

}
