<?php

namespace App\Http\Controllers;

use Request;
use DB;
use Validator;
use Auth;
use App\Http\Controllers\STPCardsController;

class BEController extends Controller
{
    public function loginForm(){
        return View('login');
    }

    public function logout(){
        if(Auth::check()) {
            Auth::logout();
            return Redirect('/login');
        } else {
            return Redirect('/login');
        }
    }

    public function login(){
        $remember = false;
        if(Request::get('remember')) { $remember = true; }

        if(Auth::viaRemember()) {
            return redirect()->intended('/');
        }

        if(Auth::attempt(['email' => Request::get('username'), 'password' => Request::get('password') ],$remember)  ) {
            return Redirect()->intended('/');
        } else {
            return Redirect()->back()->withInput()->with('message',['type'=>'warning', 'icon' => 'warning','title' => 'Alert' , 'description' => 'The username or password is incorrect. Please try again.']);
        }
    }

    public function dashboard(){
        $customers = \DB::table('customers')->orderBy('name','asc')->paginate(50);
        $STPCardsController = new STPCardsController();
        foreach($customers as $key => $customer){
            $lotalty_program = $STPCardsController->getCustomerLoyaltyPoints($customer);
            $customer->total_qty_purchases = \DB::table('purchase_history')->where('customer_id',$customer->id)->count();
            $customer->total_amount_purchases = \DB::table('purchase_history')->where('customer_id',$customer->id)->sum('total');
            $customer->loyalty_points = $lotalty_program->points;
        }

        $start_date = Request::get('start_date',date('Y-m-01',strtotime('-60 months')));
        $end_date = Request::get('end_date',date('Y-m-d'));

        $average_spend_per_month = DB::table('purchase_history')
            ->select(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m") as month, AVG(total) as average_spend'))
            ->whereBetween('purchase_date', [$start_date, $end_date])
            ->groupBy(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m")'))
            ->orderBy('purchase_date','asc')
            ->get();

        $total_loyalty_points_per_month = DB::table('purchase_history')
            ->select(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m") as month, 
        FLOOR(SUM(total) / 10) as points_from_amount, 
        FLOOR(COUNT(*) / 10) * 10 as bonus_points, 
        FLOOR(SUM(total) / 10) + FLOOR(COUNT(*) / 10) * 10 as total_loyalty_points'))
            ->whereDate('purchase_date', '>=', '2022-01-01')
            ->groupBy(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m")'))
            ->orderBy('purchase_date','asc')
            ->get();

        $statistics = new \stdClass();
        $statistics->years = [];
        $statistics->years_labels = [];
        $statistics->months = [];
        $statistics->months_labels = [];
        $statistics->loyalty_points_months = [];

        foreach($total_loyalty_points_per_month as $points_per_month){
            $statistics->months[] = $points_per_month->month.'-01';
            $statistics->months_labels[] = date('M', strtotime($points_per_month->month.'-01'));
            $statistics->loyalty_points_months[] = $points_per_month->total_loyalty_points;
        }

        return View('dashboard')
            ->with('customers', $customers)
            ->with('average_spend_per_month', $average_spend_per_month)
            ->with('total_loyalty_points_per_month', $total_loyalty_points_per_month)
            ->with('statistics', $statistics);
    }
}
