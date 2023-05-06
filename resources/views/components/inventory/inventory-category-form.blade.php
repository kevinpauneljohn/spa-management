@if($formDefault === true)
    <form class="category-form" id="category-form"> @csrf
        @endif

        <x-adminlte-input type="text" name="name" label="Category Name" fgroup-class="name" id="name"/>
        <x-adminlte-textarea name="description" label="Description" fgroup-class="description" id="description"/>

        @if($formDefault === true) </form> @endif


@section('plugins.ClearErrors',true)
@section('plugins.Toastr',true)
@section('plugins.CustomAlert',true)
@section('plugins.Categories',true)
