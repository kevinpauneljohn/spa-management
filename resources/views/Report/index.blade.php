
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
  <div class="row">
    <div class="col-lg-3">
      <h1>Sales Report</h1>
      <input type="hidden" class="form-control salesOwnerId" value="{{$owner}}">
      <input type="hidden" class="form-control salesSpaId">
    </div>
    <div class="col-lg-9">
      <ul class="nav nav-tabs float-right" id="tabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active spaTabs" id="all" data-toggle="pill" href="#all" role="tab" aria-controls="all" aria-selected="true">All Store</a>
        </li>
        @if (count($spas) > 1)
          @foreach ($spas as $spa)
          <li class="nav-item">
            <a class="nav-link spaTabs" id="{{$spa->id}}" data-toggle="pill" href="#{{$spa->name}}" role="tab" aria-controls="{{$spa->name}}" aria-selected="false">{{$spa->name}}</a>
          </li>
          @endforeach
        @endif
      </ul>
    </div>
  </div>
@stop

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title">Store Transactions</h3>
              <!-- <a href="javascript:void(0);">View Report</a> -->
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <p class="d-flex flex-column">
                <span class="text-bold text-lg currentMonthVisitors text-orange"></span>
                <span>Visitors {{date('F, Y')}}</span>
              </p>
              <p class="ml-auto d-flex flex-column text-right">
                <span class="textVisitorPercentage">
                  <i class="iconVisitorPercentage fas"></i>
                  <span class="lastMonthVisitorPercentage"></span>
                </span>
                <span class="text-muted">Since last month</span>
              </p>
            </div>
            <!-- /.d-flex -->

            <div class="position-relative mb-4">
              <canvas id="visitors-chart" height="200"></canvas>
            </div>

            <div class="d-flex flex-row justify-content-end">
              <span class="mr-2">
                <i class="fas fa-square text-success"></i> This year
              </span>

              <span>
                <i class="fas fa-square text-gray"></i> Last year
              </span>
            </div>
          </div>
        </div>
        <!-- /.card -->

      </div>

      <!-- /.col-md-6 -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title">Sales</h3>
              <!-- <a href="javascript:void(0);">View Report</a> -->
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <p class="d-flex flex-column">
                <span class="text-bold text-lg currentMonthSales text-orange"></span>
                <span>Sales {{date('F, Y')}}</span>
              </p>
              <p class="ml-auto d-flex flex-column text-right">
                <span class="textPercentage">
                  <i class="iconPercentage fas"></i>
                  <span class="lastMonthPercentage"></span>
                </span>
                <span class="text-muted">Since last month</span>
              </p>
            </div>
            <!-- /.d-flex -->

            <div class="position-relative mb-4">
              <canvas id="sales-chart" height="200"></canvas>
            </div>

            <div class="d-flex flex-row justify-content-end">
              <span class="mr-2">
                <i class="fas fa-square text-success"></i> This year
              </span>

              <span>
                <i class="fas fa-square text-gray"></i> Last year
              </span>
            </div>
          </div>
        </div>

        <!-- /.card -->
{{--        <div class="card">--}}
{{--          <div class="card-header border-0">--}}
{{--            <h3 class="card-title">Store Overview</h3>--}}
{{--            <div class="card-tools">--}}
{{--              <a href="#" class="btn btn-sm btn-tool">--}}
{{--                <i class="fas fa-download"></i>--}}
{{--              </a>--}}
{{--              <a href="#" class="btn btn-sm btn-tool">--}}
{{--                <i class="fas fa-bars"></i>--}}
{{--              </a>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--          <div class="card-body">--}}
{{--            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">--}}
{{--              <p class="text-success text-xl">--}}
{{--                <i class="ion ion-ios-refresh-empty"></i>--}}
{{--              </p>--}}
{{--              <p class="d-flex flex-column text-right">--}}
{{--                <span class="font-weight-bold">--}}
{{--                  <i class="ion ion-android-arrow-up text-success"></i> 12%--}}
{{--                </span>--}}
{{--                <span class="text-muted">CONVERSION RATE</span>--}}
{{--              </p>--}}
{{--            </div>--}}
{{--            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">--}}
{{--              <p class="text-warning text-xl">--}}
{{--                <i class="ion ion-ios-cart-outline"></i>--}}
{{--              </p>--}}
{{--              <p class="d-flex flex-column text-right">--}}
{{--                <span class="font-weight-bold">--}}
{{--                  <i class="ion ion-android-arrow-up text-warning"></i> 0.8%--}}
{{--                </span>--}}
{{--                <span class="text-muted">SALES RATE</span>--}}
{{--              </p>--}}
{{--            </div>--}}
{{--            <div class="d-flex justify-content-between align-items-center mb-0">--}}
{{--              <p class="text-danger text-xl">--}}
{{--                <i class="ion ion-ios-people-outline"></i>--}}
{{--              </p>--}}
{{--              <p class="d-flex flex-column text-right">--}}
{{--                <span class="font-weight-bold">--}}
{{--                  <i class="ion ion-android-arrow-down text-danger"></i> 1%--}}
{{--                </span>--}}
{{--                <span class="text-muted">REGISTRATION RATE</span>--}}
{{--              </p>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}
      </div>
      <!-- /.col-md-6 -->
    </div>
    <!-- /.row -->

      <div class="row">
          @foreach($spas as $spa)
              <div class="col-lg-6">
                  <div class="card">
                      <div class="card-header border-0">
                          <h3 class="card-title">Sales/Expenses/Profit Report - <span class="text-primary">{{ucwords($spa->name)}}</span></h3>
                          <div class="card-tools">
                              <a href="#" class="btn btn-tool btn-sm">
                                  <i class="fas fa-download"></i>
                              </a>
                              <a href="#" class="btn btn-tool btn-sm">
                                  <i class="fas fa-bars"></i>
                              </a>
                          </div>
                      </div>
                      <div class="card-body table-responsive">
                          <x-sales.profit-report :spaId="$spa->id"/>
                      </div>
                  </div>
              </div>
          @endforeach
      </div>
  </div>
@stop

@section('footer')
    <strong>Copyright © 2023 <a href="https://adminlte.io">DHG IT Solutions</a>.</strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="{{asset('js/reports/Chart.min.js')}}"></script>
    <script src="{{asset('js/reports/sales.data.js')}}"></script>

    <script>
      $(function() {
        loadData();
        $(document).on('click', '.spaTabs', function (e) {
          let id = this.id;
          if (id === 'all') {
            $('.salesSpaId').val('');
          } else {
            $('.salesSpaId').val(id);
          }

          loadData();
        });
      });
    </script>
@stop
