@if($sale->payment_status !== 'completed')
    <button class="btn btn-warning" id="cancel-sales-btn">
        {{$slot}}
    </button>
    @once
        @push('js')
            <script>
                $(document).on('click','#cancel-sales-btn', function(){
                    {{--console.log('{{$sale->id}}')--}}

                    swal.fire({
                        title: "Cancel & Delete Sales Instance?",
                        html:
                            '<p>This will delete the created sales instance</p>Click <b class="text-info">YES</b>, to confirm',
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                        reverseButtons: !0
                    }).then(function (e) {
                        if (e.value === true) {
                            $.ajax({
                                url: '/point-of-sale/{{$sale->id}}',
                                type: 'delete',
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            }).done((sales) => {
                                console.log(sales)
                                if(sales.success === true)
                                {
                                    Swal.fire(
                                        sales.message,
                                        '',
                                        'success'
                                    )
                                    window.location.replace('/point-of-sale/{{$sale->spa_id}}/');
                                }
                                else if(sales.success === false){
                                    Swal.fire(
                                        sales.message,
                                        '',
                                        'warning'
                                    )
                                }
                            }).fail(function(xhr, data, status){
                                if(data === 'error')
                                {
                                    Swal.fire(
                                        'Warning',
                                        'You must remove all transactions before deleting the sales instance',
                                        'warning'
                                    )
                                }
                            });

                        } else {
                            e.dismiss;
                        }

                    }, function (dismiss) {
                        return false;
                    })
                });
            </script>
        @endpush
    @endonce
@endif

