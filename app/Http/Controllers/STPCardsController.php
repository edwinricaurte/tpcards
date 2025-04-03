<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Request;
use DB;
use File;
use Validator;

class STPCardsController extends Controller
{
    public function createTestUser(){
        if(!\DB::table('users')->where('email','anthony@stpcards.com')->exists()){
            \DB::table('users')->where('email','anthony@stpcards.com')->insert([
                'name' => 'Anthony',
                'email'=>'anthony@stpcards.com',
                'password' => Hash::make('test123@'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
    public function importCustomers(){
        if(File::exists(storage_path('app/customers.csv'))){
            $customers = $this->convertCSVtoArray(storage_path('app/customers.csv'));

            $log_file = fopen(storage_path('/customers-importation-log.txt'), 'a');

            $rules = [
                'name' => 'required|string|regex:/^[\pL\s]+$/u|max:255',
                'email' => 'required|email',
                'phone_number' => 'required|string|regex:/^[0-9.\-_]+$/|min:10|max:255',
            ];

            foreach($customers as $customer){
                $validator = Validator::make($customer, $rules);

                if($validator->fails()){
                    fwrite($log_file, date('m/d/Y g:i A')."\n");
                    fwrite($log_file, json_encode($validator->errors())."\n\n");

                    continue;
                }
                $customer['email'] = strtolower($customer['email']);
                $customer['phone_number'] = $this->formatPhoneNumber($customer['phone_number']);
                $customer['created_at'] = date('Y-m-d H:i:s');
                if(!\DB::table('customers')->where('email', $customer['email'])->exists()){
                    \DB::table('customers')->insert($customer);
                } else {
                    fwrite($log_file, date('m/d/Y g:i A')."\n");
                    fwrite($log_file, date('Existent Customer')."\n");
                    fwrite($log_file, json_encode($customer)."\n\n");
                }
            }

            fclose($log_file);

        }

        if(File::exists(storage_path('app/purchase_history.csv'))){
            $purchase_history = $this->convertCSVtoArray(storage_path('app/purchase_history.csv'));

            foreach($purchase_history as $purchase){
                if($customer = \DB::table('customers')->where('email',$purchase['customer_email'])->first()){
                    \DB::table('purchase_history')->insert([
                        'customer_id' => $customer->id,
                        'price' => $purchase['price'],
                        'quantity' => $purchase['quantity'],
                        'total' => $purchase['total'],
                        'purchase_date' => date('Y-m-d', strtotime($purchase['date'])),
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
        $this->updateCustomersLoyaltyPoints();

    }

    function updateCustomersLoyaltyPoints(){
        $customers = \DB::table('customers')->get();
        foreach($customers as $customer){
            $lotalty_program = $this->getCustomerLoyaltyPoints($customer);
            \DB::table('customers')->where('id',$customer->id)->update([
                'loyalty_points' => $lotalty_program->points
            ]);
        }
    }

    function getCustomerLoyaltyPoints($customer = null){

        $lotalty_program = new \stdClass();
        $lotalty_program->purchases_qty = 0;
        $lotalty_program->total_purchases = 0;
        $lotalty_program->points = 0;

        if(gettype($customer) == 'object'){
            $lotalty_program->purchases_qty = \DB::table('purchase_history')->where('customer_id',$customer->id)->whereDate('purchase_date', '>=', '2022-01-01')->count();
            $lotalty_program->total_purchases = \DB::table('purchase_history')->where('customer_id',$customer->id)->whereDate('purchase_date', '>=', '2022-01-01')->sum('total');
            $lotalty_program->points = $this->calculateLoyaltyPoints($lotalty_program->total_purchases,$lotalty_program->purchases_qty);

            return  $lotalty_program;
        }
        return $lotalty_program;
    }

    function calculateLoyaltyPoints($amountSpent, $totalPurchases) {
        // Round down the amount spent to the nearest whole number
        $roundedAmount = floor($amountSpent);

        // Calculate loyalty points based on the amount spent ($10 per point)
        $pointsFromAmount = floor($roundedAmount / 10);

        // Calculate bonus loyalty points for every 10 purchases made
        $bonusPoints = floor($totalPurchases / 10) * 10;

        // Return the total loyalty points (amount-based points + bonus points)
        return $pointsFromAmount + $bonusPoints;
    }

    public function formatPhoneNumber($phone_number){
        $phone_number = preg_replace('/\D/', '', $phone_number);
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1-$2-$3', $phone_number);
    }

    public function convertCSVtoArray($file_location){
        $rows = array_map('str_getcsv', file($file_location));
        $header = array_shift($rows);

        foreach ($header as $key => $value) {
            $header[$key] = \Str::slug($value, '_');
        }

        $items = [];

        foreach ($rows as $row) {
            $items[] = $this->array_combine_special($header, $row);
        }
        return $items;
    }

    public static function array_combine_special($a, $b, $pad = TRUE)
    {
        $acount = count($a);
        $bcount = count($b);
        // more elements in $a than $b but we don't want to pad either
        if(!$pad) {
            $size = ($acount > $bcount) ? $bcount : $acount;
            $a = array_slice($a, 0, $size);
            $b = array_slice($b, 0, $size);
        } else {
            // more headers than row fields
            if($acount > $bcount) {
                $more = $acount - $bcount;
                // how many fields are we missing at the end of the second array?
                // Add empty strings to ensure arrays $a and $b have same number of elements
                $more = $acount - $bcount;
                for($i = 0; $i < $more; $i++) {
                    $b[] = "";
                }
                // more fields than headers
            } else if($acount < $bcount) {
                $more = $bcount - $acount;
                // fewer elements in the first array, add extra keys
                for($i = 0; $i < $more; $i++) {
                    $key = 'extra_field_0' . $i;
                    $a[] = $key;
                }

            }
        }
        return array_combine($a, $b);
    }
}
