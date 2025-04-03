@extends('layouts.default')
@section('title','Dashboard')
@section('head')
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/vendors/css/charts/chartist.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/vendors/css/charts/chartist-plugin-tooltip.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/pages/dashboard-analytics.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/vendors/css/tables/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
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
                                <div class="input-group">
                                    <input type="text" name="date_range" id="date_range_filter" class="form-control" value="{{old('date_range',date('m/d/Y',strtotime('-12 months')).' - '.date('m/d/Y'))}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <span class="ft-calendar"></span>
                                        </span>
                                    </div>
                                </div>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title text-bold-700 my-2">Average Spend</h5>
                        <div id="as_title"></div>
                    </div>

                    <div class="card">
                        <div class="card-content">
                            <div id="recent-projects" class="media-list position-relative">
                                <div class="table-responsive">
                                    <table class="table table-padded table-xl mb-0" id="average_spend_table">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">Month</th>
                                            <th class="border-top-0">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-7 col-md-12">

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title text-bold-700 my-2">Loyalty Points per Month</h5>
                        <div id="lppm_title"></div>
                    </div>

                    <div class="card">
                        <div class="card-content">
                            <div id="recent-projects" class="media-list position-relative">
                                <div class="table-responsive">
                                    <table class="table table-padded table-xl mb-0" id="loyalty_points_table">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0" style="vertical-align: middle;">Month</th>
                                            <th class="border-top-0" style="vertical-align: middle;">Points</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br></br>

                    <h5 class="card-title text-bold-700 my-2">Customers</h5>
                    <div class="card">
                        <div class="card-content">
                            <div id="recent-projects" class="media-list position-relative">
                                <div class="table-responsive">
                                    <table class="table table-padded table-xl mb-0" id="customers_table">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0" style="vertical-align: middle;">Customer</th>
                                            <th class="border-top-0" style="vertical-align: middle;">Purchases Qty</th>
                                            <th class="border-top-0" style="vertical-align: middle;">Total Purchases</th>
                                            <th class="border-top-0" style="vertical-align: middle;">Loyalty Points<div><sup>01 January 2022</sup></div></th>
                                        </tr>
                                        </thead>
                                        <tbody>

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
<script src="/dynamic/app-assets/vendors/js/tables/datatable/datatables.min.js" type="text/javascript"></script>

<script src="/dynamic/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js" type="text/javascript"></script>
<script src="/dynamic/app-assets/vendors/js/pickers/daterange/daterangepicker.js" type="text/javascript"></script>

<script>
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

    $('#date_range_filter').daterangepicker({
        autoApply: false
    });
    $('#date_range_filter').on('apply.daterangepicker', function(ev, picker) {
        getMonthlyStatistics();
    });

    async function getMonthlyStatistics(){
        const response = await fetch('/api/get-loyalty-points-stats', {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                start_date: $('#date_range_filter').data('daterangepicker').startDate.format('L'),
                end_date: $('#date_range_filter').data('daterangepicker').endDate.format('L')
            })
        });
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        generateMonthlyStatistics(json.loyalty_points_months, json.months_labels);

        var average_spend_table = $('#average_spend_table').DataTable();
        average_spend_table.ajax.reload();

        var loyalty_points_table = $('#loyalty_points_table').DataTable();
        loyalty_points_table.ajax.reload();

        let html_daterange = $('#date_range_filter').data('daterangepicker').startDate.format('MMMM YYYY')+' to '+$('#date_range_filter').data('daterangepicker').endDate.format('MMMM YYYY');

        document.getElementById('as_title').innerHTML = html_daterange;
        document.getElementById('lppm_title').innerHTML = html_daterange;
    }

    function generateMonthlyStatistics(monthly_statistics, labels) {

        var monthly_statistics = new Chartist.Line('#project-stats', {
            labels : labels,
            series: [monthly_statistics]
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

        monthly_statistics.on('created', function (data) {
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
    }

    $( document ).ready(function() {
        getMonthlyStatistics();
        $('#customers_table').DataTable( {
            columnDefs: [
                { name: 'name', targets: 0 },
                { name: 'purchases_qty', targets: 1, className: 'text-center','orderable': false },
                { name: 'total_purchases', targets: 2, className: 'text-center','orderable': false },
                { name: 'loyalty_points', targets: 3, className: 'text-center' },
            ],
            order: [[0, 'asc']],
            pageLength: 10,
            searchDelay: 150,
            ajax: "/get-customers",
            processing: true,
            serverSide: true
        });
        $('#average_spend_table').DataTable( {
            columnDefs: [
                { name: 'month', targets: 0 },
                { name: 'amount', targets: 1, className: 'text-center' },
            ],
            order: [[0, 'asc']],
            pageLength: 25,
            searchDelay: 150,
            ajax: {
                url: "/get-average-spend",
                data: function(d){
                    d.start_date = $('#date_range_filter').data('daterangepicker').startDate.format('L'),
                    d.end_date = $('#date_range_filter').data('daterangepicker').endDate.format('L')
                }
            },
            processing: true,
            serverSide: true,
            searching: false,
        });

        $('#loyalty_points_table').DataTable( {
            columnDefs: [
                { name: 'month', targets: 0 },
                { name: 'points', targets: 1, className: 'text-center' },
            ],
            order: [[0, 'asc']],
            pageLength: 10,
            searchDelay: 150,
            ajax: {
                url: "/get-loyalty-points",
                data: function(d){
                    d.start_date = $('#date_range_filter').data('daterangepicker').startDate.format('L'),
                    d.end_date = $('#date_range_filter').data('daterangepicker').endDate.format('L')
                }
            },
            processing: true,
            serverSide: true,
            searching: false,
        });
    });
</script>
@stop