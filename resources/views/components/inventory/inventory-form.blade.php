@if($formDefault === true)
    <form class="inventory-form" id="inventory-form"> @csrf
        @endif

    <x-adminlte-select name="spa_id"  fgroup-class="spa_id" label="Spa">

        @if(!$spaId)
            <option value=""> --Select--</option>
            @foreach($spas as $spa)
                <option value="{{$spa->id}}"> {{$spa->name}}</option>
            @endforeach

        @else
            <option value="{{$spas->id}}"> {{$spas->name}}</option>
        @endif

    </x-adminlte-select>

    <x-adminlte-input type="text" name="name" label="Item Name" fgroup-class="name" id="name"/>
    <x-adminlte-textarea name="description" label="Description" fgroup-class="description" id="description"/>
    <x-adminlte-input type="number" name="quantity" label="Quantity" fgroup-class="quantity" id="quantity" min="0"/>
    <x-adminlte-input type="number" name="restock_limit" label="Re-stock limit" fgroup-class="restock_limit" id="restock_limit" min="0"/>

    <x-adminlte-select name="unit"  fgroup-class="unit" label="Unit" id="unit">
        <option value=""> --Select--</option>
        @foreach($measurements as $measurement)
            <option value="{{$measurement->singular}}"> {{$measurement->singular}}</option>
        @endforeach
    </x-adminlte-select>

    <x-adminlte-select name="category"  fgroup-class="category" label="Category" id="category">
        <option value=""> --Select--</option>
        @foreach($categories as $category)
            <option value="{{$category->id}}"> {{$category->name}}</option>
        @endforeach
    </x-adminlte-select>

    <x-adminlte-input type="text" name="sku" label="Stock keeping Unit" fgroup-class="sku" id="sku"/>
    @if($formDefault === true) </form> @endif


@section('plugins.Inventories',true)
@section('plugins.ClearErrors',true)
@section('plugins.Toastr',true)
@section('plugins.CustomAlert',true)
