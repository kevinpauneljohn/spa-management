<div id="profit-report-component">
    <x-sales.profit-date-range spaId="774a6ccf-d0e6-4cb7-a56c-9f0f470d3272"/>

    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
        <p class="text-success text-xl">
            <i class="ion ion-ios-refresh-empty"></i>
        </p>
        <p class="d-flex flex-column text-right">
                <span class="font-weight-bold">
                  <i class="ion ion-android-arrow-up text-success"></i> <span id="total-sales">{{number_format($total_sales,2)}}</span>
                </span>
            <span class="text-muted">Total Sales</span>
        </p>
    </div>
    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
        <p class="text-warning text-xl">
            <i class="ion ion-ios-cart-outline"></i>
        </p>
        <p class="d-flex flex-column text-right">
                <span class="font-weight-bold">
                  <i class="ion ion-android-arrow-up text-warning"></i> <span id="total-expenses">{{number_format($expenses,2)}}</span>
                </span>
            <span class="text-muted">Total Expenses</span>
        </p>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-0">
        <p class="text-danger text-xl">
            <i class="ion ion-ios-people-outline"></i>
        </p>
        <p class="d-flex flex-column text-right">
                <span class="font-weight-bold">
                  <i class="ion ion-android-arrow-down text-danger"></i> <span id="total-profit">{{number_format($profit,2)}}</span>
                </span>
            <span class="text-muted">Total Profit</span>
        </p>
    </div>
</div>
