@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')

@stop
<style>

</style>
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6 mt-3">
            <h3 class="text-cyan">{{ucwords($pageTitle)}}</h3>
        </div>
        <div class="col-sm-6 mt-3">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('owner.my.spas')}}">Spa</a> </li>
                <li class="breadcrumb-item active">{{ucwords($spa->name)}} </li>
            </ol>
        </div>
    </div>


    <form class="form-start-shift">
        @csrf
        <div class="modal fade" id="startShift" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-olive">
                        <h4 class="modal-title">Start Shift</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group start_money">
                            <label for="start_money">Money on hand</label>
                            <input type="number" name="start_money" id="start_money" min="0" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <span class="float-left">

                        </span>
                        <span class="float-right">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </span>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
@stop
@section('plugins.CustomCSS',true)
@section('css')
@stop

@section('js')
    @if(auth()->check())
        <script>
            let startShiftModal = $('#startShift')
            let shiftForm = $('.form-start-shift')
            $(document).ready(function(){
                startShiftModal.modal('toggle');
            });

            $(document).on('submit','.form-start-shift',function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                $.ajax({
                    url: '/sales-shift',
                    type: 'post',
                    data: data,
                    beforeSend: function(){
                        startShiftModal.find('.text-danger').remove();
                        startShiftModal.find('.is-invalid').removeClass('is-invalid')
                        shiftForm.find('button').attr('disabled',true);
                    }
                }).done( salesShift => {
                    console.log(salesShift)
                    if(salesShift.success === true)
                    {
                        Swal.fire('Hooray', salesShift.message, 'success')
                        setTimeout(function(){
                            window.location.replace('/point-of-sale/{{auth()->user()->spa_id}}');
                        },1500)
                    }else if(salesShift.success === false)
                    {
                        Swal.fire('Warning', salesShift.message, 'warning')
                    }
                    startShiftModal.modal('toggle')
                }).fail(xhr => {
                    $.each(xhr.responseJSON.errors, function(key, value)
                    {
                        console.log(value)
                        startShiftModal.find('.'+key).append('<p class="text-danger">'+value+'</p>')
                        startShiftModal.find('#'+key).addClass('is-invalid')
                    })
                }).always(() => {
                    shiftForm.find('button').attr('disabled',false);
                });
            })
        </script>
    @endif
@stop
