<div>
    {{now()->format('Y')}}
    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
        <p class="text-success text-xl">
            <i class="ion ion-ios-refresh-empty"></i>
        </p>
        <p class="d-flex flex-column text-right">
                <span class="font-weight-bold">
                  <i class="ion ion-android-arrow-up text-success"></i> {{number_format($sales,2)}}
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
                  <i class="ion ion-android-arrow-up text-warning"></i> {{number_format($expenses,2)}}
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
                  <i class="ion ion-android-arrow-down text-danger"></i> {{number_format($profit)}}
                </span>
            <span class="text-muted">Total Profit</span>
        </p>
    </div>
</div>
