<?php

namespace Database\Seeders;

use App\Models\Qad\SdoAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SdoAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sdos = [
            [1, '01', 'CAVITE PROVINCE', '', '','PROVINCE'],
            [2, '02', 'LAGUNA PROVINCE', '', '',  'PROVINCE'],
            [3, '03', 'BATANGAS PROVINCE', '', '', 'PROVINCE'],
            [4, '04', 'RIZAL PROVINCE', '', '',  'PROVINCE'],
            [5, '05', 'QUEZON PROVINCE', '', '',  'PROVINCE'],
            [6, '01A', 'BACOOR CITY', '', '', 'CITY'],
            [7, '01B', 'CAVITE CITY', '', '',   'CITY'],
            [8, '01C', 'DASMARINAS CITY', '', '',   'CITY'],
            [9, '01D', 'GENERAL TRIAS CITY', '', '',  'CITY'],
            [10, '01E', 'IMUS CITY', '', '',   'CITY'],
            [11, '02A', 'BIÑAN CITY', '', '',   'CITY'],
            [12, '02B', 'CABUYAO CITY', '', '',  'CITY'],
            [13, '02C', 'CALAMBA CITY', '', '',   'CITY'],
            [14, '02D', 'SAN PEDRO CITY', '', '',   'CITY'],
            [15, '02E', 'SANTA ROSA CITY', '', '',   'CITY'],
            [16, '03A', 'BATANGAS CITY', '', '',  'CITY'],
            [17, '03B', 'LIPA CITY', '', '',  'CITY'],
            [18, '03C', 'SANTO TOMAS CITY', '', '',  'CITY'],
            [19, '03D', 'TANUAN CITY', '', '',  'CITY'],
            [20, '04A', 'ANTIPOLO CITY', '', '',   'CITY'],
            [21, '05A', 'LUCENA CITY', '', '',   'CITY'],
            [22, '05B', 'TAYABAS CITY', '', '',  'CITY'],
            [23, '02F', 'SAN PABLO CITY', '', '',  'CITY'],
        ];

        foreach($sdos as $sdo){
            SdoAccount::query()->create([
                'id'=>$sdo[0],
                "sdo_code"  => $sdo[1],
                "sdo_name"     => $sdo[2],
                "sds_name"      => $sdo[3],
                "asds_name"     => $sdo[4],
                "email"     => $sdo[2].'@gmail.com',
                "type"          => $sdo[5],
                "status"          =>'active',
                'password'=>Hash::make( $sdo[1].'@depedcalabarzon'),
            ]);
        }
    }
}
