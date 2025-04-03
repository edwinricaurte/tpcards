<?php

namespace App\Http\Controllers;

use Request;
use Response;
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

    public function dashboardV2(){
        return View('dashboard-v2');
    }

    public function getCustomers(){
        $request = Request::all();

        $q = $request['search']['value'];

        $query = \DB::table('customers');

        if($q){
            $query->whereRaw("(customers.name like '%$q%' or customers.email like '%$q%' or customers.phone_number like '%$q%')");
        }

        if(isset($request['order'])) {
            if(isset($request['columns'][$request['order'][0]['column']]['name'])){
                $order_by = $request['columns'][$request['order'][0]['column']]['name'];
                $query->orderBy($order_by, $request['order'][0]['dir']);
            }
        }

        $customers = $query->get();
        $total_results = count($customers);
        $customers = $customers->splice(Request::get('start',0),Request::get('length',25));

        $r_object = new \stdClass();
        $r_object->draw = (int)$request['draw'];
        $r_object->recordsTotal = $total_results;
        $r_object->recordsFiltered = $total_results;
        $r_object->data = [];

        $STPCardsController = new STPCardsController();

        foreach($customers as $key => $customer){
            $lotalty_program = $STPCardsController->getCustomerLoyaltyPoints($customer);
            $r_object->data[] = [
                $customer->name,
                \DB::table('purchase_history')->where('customer_id',$customer->id)->count(),
                "$".number_format(\DB::table('purchase_history')->where('customer_id',$customer->id)->sum('total'),2),
                $customer->loyalty_points,
            ];
        }
        return Response::json($r_object);
    }

    public function getLoyaltyPointsStats(){
        if(Request::get('start_date') and Request::get('end_date')){
            $request_start_date = date('Y-m-d',strtotime(Request::get('start_date')));
            $request_end_date = date('Y-m-d',strtotime(Request::get('end_date')));
        } else {
            $request_start_date = date('Y-m-01',strtotime('-60 months'));
            $request_end_date = date('Y-m-d');
        }

        $query = DB::table('purchase_history');
        $query->select(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m") as month, 
        FLOOR(SUM(total) / 10) as points_from_amount, 
        FLOOR(COUNT(*) / 10) * 10 as bonus_points, 
        FLOOR(SUM(total) / 10) + FLOOR(COUNT(*) / 10) * 10 as total_loyalty_points'));
        $query->whereDate('purchase_date', '>=', '2022-01-01');

        $query->whereBetween('purchase_date', [$request_start_date, $request_end_date]);

        $query->groupBy(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m")'));
        $query->orderBy('purchase_date','asc');
        $total_loyalty_points_per_month = $query->get();

        $statistics = new \stdClass();
        $statistics->months = [];
        $statistics->months_labels = [];
        $statistics->loyalty_points_months = [];

        foreach($total_loyalty_points_per_month as $points_per_month){
            $statistics->months[] = $points_per_month->month.'-01';
            $statistics->months_labels[] = date('M', strtotime($points_per_month->month.'-01'));
            $statistics->loyalty_points_months[] = $points_per_month->total_loyalty_points;
            $statistics->start_date = $request_start_date;
            $statistics->end_date = $request_end_date;
        }
        return Response::json($statistics);
    }

    public function getAverageSpend(){

        if(Request::get('start_date') and Request::get('end_date')){
            $request_start_date = date('Y-m-d',strtotime(Request::get('start_date')));
            $request_end_date = date('Y-m-d',strtotime(Request::get('end_date')));
        } else {
            $request_start_date = date('Y-m-01',strtotime('-60 months'));
            $request_end_date = date('Y-m-d');
        }

        $request = Request::all();

        $query = \DB::table('purchase_history');
        $query->select(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m") as month, AVG(total) as amount'));
        $query->whereBetween('purchase_date', [$request_start_date, $request_end_date]);
        $query->groupBy(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m")'));


        if(isset($request['order'])) {
            if(isset($request['columns'][$request['order'][0]['column']]['name'])){
                $order_by = $request['columns'][$request['order'][0]['column']]['name'];
                $query->orderBy($order_by, $request['order'][0]['dir']);
            }
        } else {
            $query->orderBy('purchase_date','asc');
        }

        $average_spend_per_month = $query->get();
        $total_results = count($average_spend_per_month);
        $average_spend_per_month = $average_spend_per_month->splice(Request::get('start',0),Request::get('length',25));

        $r_object = new \stdClass();
        $r_object->draw = (int)$request['draw'];
        $r_object->recordsTotal = $total_results;
        $r_object->recordsFiltered = $total_results;
        $r_object->data = [];

        foreach($average_spend_per_month as $month){
            $r_object->data[] = [
                date('M Y',strtotime($month->month.'-01')),
                "$".number_format($month->amount,2)
            ];
        }

        return $r_object;
    }

    public function getLoyaltyPoints(){
        if(Request::get('start_date') and Request::get('end_date')){
            $request_start_date = date('Y-m-d',strtotime(Request::get('start_date')));
            $request_end_date = date('Y-m-d',strtotime(Request::get('end_date')));
        } else {
            $request_start_date = date('Y-m-01',strtotime('-60 months'));
            $request_end_date = date('Y-m-d');
        }

        $request = Request::all();

        $query = DB::table('purchase_history')
            ->select(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m") as month, 
        FLOOR(SUM(total) / 10) as points_from_amount, 
        FLOOR(COUNT(*) / 10) * 10 as bonus_points, 
        FLOOR(SUM(total) / 10) + FLOOR(COUNT(*) / 10) * 10 as points'));
        $query->whereDate('purchase_date', '>=', '2022-01-01');
        $query->whereBetween('purchase_date', [$request_start_date, $request_end_date]);
        $query->groupBy(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m")'));

        if(isset($request['order'])) {
            if(isset($request['columns'][$request['order'][0]['column']]['name'])){
                $order_by = $request['columns'][$request['order'][0]['column']]['name'];
                $query->orderBy($order_by, $request['order'][0]['dir']);
            }
        } else {
            $query->orderBy('purchase_date','asc');
        }

        $total_loyalty_points_per_month = $query->get();
        $total_results = count($total_loyalty_points_per_month);
        $total_loyalty_points_per_month = $total_loyalty_points_per_month->splice(Request::get('start',0),Request::get('length',25));

        $r_object = new \stdClass();
        $r_object->draw = (int)$request['draw'];
        $r_object->recordsTotal = $total_results;
        $r_object->recordsFiltered = $total_results;
        $r_object->data = [];

        foreach($total_loyalty_points_per_month as $month){
            $r_object->data[] = [
                date('M Y',strtotime($month->month.'-01')),
                $month->points
            ];
        }

        return $r_object;
    }
}
