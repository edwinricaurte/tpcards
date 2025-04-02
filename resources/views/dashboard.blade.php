@extends('layouts.default')
@section('title','Dashboard')
@section('head')
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/vendors/css/charts/chartist.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/vendors/css/charts/chartist-plugin-tooltip.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/pages/dashboard-analytics.css">
@stop
@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Statistics</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">

                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body p-0 pb-0">
                                <div class="chartist">
                                    <div id="project-stats" class="height-350 areaGradientShadow1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card pull-up bg-gradient-directional-danger">
                                <div class="card-header bg-hexagons-danger">
                                    <h4 class="card-title white">Analytics</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li>
                                                <a class="btn btn-sm btn-white danger box-shadow-1 round btn-min-width pull-right" href="javascript:;" target="_blank"><i class="ft-bar-chart pl-1"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show bg-hexagons-danger">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-center width-100">
                                                <div id="Analytics-donut-chart" class="height-100 donutShadow"></div>
                                            </div>
                                            <div class="media-body text-right mt-1">
                                                <h3 class="font-large-2 white">{{\DB::table('customers')->count()}}</h3>
                                                <h6 class="mt-1"><span class="text-muted white">Total Customers</span></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card pull-up border-top-info border-top-3 rounded-0">
                                <div class="card-header">
                                    <h4 class="card-title">Purchases</h4>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body p-1">
                                        <h4 class="font-large-1 text-bold-400">{{\DB::table('purchase_history')->count()}} <i class="ft-monitor float-right"></i></h4>
                                    </div>
                                    <div class="card-footer p-1">
                                        <span class="text-muted"><i class="la la-arrow-circle-o-up info"></i> Total Purchases</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row match-height">
                <div class="col-xl-4 col-lg-5 col-md-12">
                    <h5 class="card-title text-bold-700 my-2">Average Spend</h5>
                    <div class="card">
                        <div class="card-content">
                            <div id="recent-projects" class="media-list position-relative">
                                <div class="table-responsive">
                                    <table class="table table-padded table-xl mb-0" id="recent-project-table">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0" style="vertical-align: middle;">Month</th>
                                            <th class="border-top-0" style="vertical-align: middle;">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($average_spend_per_month as $month)
                                            <tr>
                                                <td>
                                                    <div>{{date('M Y',strtotime($month->month.'-01'))}}</div>
                                                </td>
                                                <td>${{number_format($month->average_spend,2,'.',',')}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-7 col-md-12">
                    <h5 class="card-title text-bold-700 my-2">Customers</h5>
                    <div class="card">
                        <div class="card-content">
                            <div id="recent-projects" class="media-list position-relative">
                                <div class="table-responsive">
                                    <table class="table table-padded table-xl mb-0" id="recent-project-table">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0" style="vertical-align: middle;">Customer</th>
                                            <th class="border-top-0" style="vertical-align: middle;">Purchases Qty</th>
                                            <th class="border-top-0" style="vertical-align: middle;">Total Purchases</th>
                                            <th class="border-top-0" style="vertical-align: middle;">Loyalty Points<div><sup>01 January 2022</sup></div></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($customers as $customer)
                                        <tr>
                                            <td>
                                                <div>{{$customer->name}}</div>
                                                <div>{{$customer->phone_number}}</div>
                                                <div>{{$customer->email}}</div>
                                            </td>
                                            <td>{{$customer->total_qty_purchases}}</td>
                                            <td>${{number_format($customer->total_amount_purchases,2,'.',',')}}</td>
                                            <td>{{$customer->loyalty_points}}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {!! $customers->render() !!}
                            </div>
                        </div>
                    </div>

                    <br><br>
                    <h5 class="card-title text-bold-700 my-2">Loyalty Points per Month</h5>
                    <div class="card">
                        <div class="card-content">
                            <div id="recent-projects" class="media-list position-relative">
                                <div class="table-responsive">
                                    <table class="table table-padded table-xl mb-0" id="recent-project-table">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0" style="vertical-align: middle;">Month</th>
                                            <th class="border-top-0" style="vertical-align: middle;">Points</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($total_loyalty_points_per_month as $tlpp_month)
                                            <tr>
                                                <td>
                                                    {{date('M Y',strtotime($tlpp_month->month.'-01'))}}
                                                </td>
                                                <td>{{$tlpp_month->total_loyalty_points}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('scripts')
<script src="/dynamic/app-assets/vendors/js/charts/chartist.min.js" type="text/javascript"></script>
<script src="/dynamic/app-assets/vendors/js/charts/chartist-plugin-tooltip.min.js" type="text/javascript"></script>
<script src="/dynamic/app-assets/vendors/js/charts/chartist-plugin-legend.js" type="text/javascript"></script>
<script>

    var sales_statistics_by_months = new Chartist.Line('#project-stats', {
        labels : {!!json_encode($statistics->months_labels)!!},
        series: [
            {!!json_encode($statistics->loyalty_points_months)!!}
        ]
    }, {
        lineSmooth: Chartist.Interpolation.simple({
            divisor: 2
        }),
        fullWidth: true,
        height: 350,
        showArea: true,
        chartPadding: {
            left: 25,
            right: 25,
            bottom: 12
        },
        axisX: {
            showGrid: false,
        },
        plugins: [
            Chartist.plugins.tooltip({
                appendToBody: true,
                pointClass: 'ct-point'
            }),
            Chartist.plugins.legend({
                legendNames: ['Loyalty Points']
            })
        ],
        low: 0,
        onlyInteger: true,
    });

    sales_statistics_by_months.on('created', function (data) {
        var defs = data.svg.querySelector('defs') || data.svg.elem('defs');
        defs.elem('linearGradient', {
            id: 'area-gradient',
            x1: 1,
            y1: 0,
            x2: 0,
            y2: 0
        }).elem('stop', {
            offset: 0,
            'stop-color': 'rgba(1,213,255, 1)'
        }).parent().elem('stop', {
            offset: 1,
            'stop-color': 'rgb(110,246,246)'
        })

        defs.elem('linearGradient', {
            id: 'area-gradient-2',
            x1: 1,
            y1: 0,
            x2: 0,
            y2: 0
        }).elem('stop', {
            offset: 0,
            'stop-color': 'rgb(0,114,184)'
        }).parent().elem('stop', {
            offset: 1,
            'stop-color': 'rgb(26,169,255)'
        })

        return defs;


    }).on('draw', function (data) {
        var circleRadius = 9;
        if (data.type === 'point') {
            var circle = new Chartist.Svg('circle', {
                cx: data.x,
                cy: data.y,
                'ct:value':data.value.y,
                r: circleRadius,
                class: data.value.y >= 10 ? 'ct-point-circle ct-point' : 'ct-point ct-point-circle-transperent'
            });
            data.element.replace(circle);
        }
        if (data.type === 'line' || data.type == 'area') {
            data.element.animate({
                d: {
                    begin: 1000,
                    dur: 1000,
                    from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                    to: data.path.clone().stringify(),
                    easing: Chartist.Svg.Easing.easeOutQuint
                }
            });
        }
    });

    function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

            const negativeSign = amount < 0 ? "-" : "";

            let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;

            return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
            console.log(e)
        }
    }
</script>
@stop