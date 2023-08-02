<a href="#" class="btn btn-primary btn-sm" id="add-sales-btn">Add Sales</a>
@section('plugins.Sweetalert2',true)
@once
    @push('js')
        <script>
            $(document).on('click','#add-sales-btn',function(button){
                button.preventDefault();
                swal.fire({
                    title: "Create Sales Instance?",
                    html:
                        'Click <b class="text-info">YES</b>, to confirm',
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel",
                    reverseButtons: !0
                }).then(function (e) {
                    if (e.value === true) {
                        $.ajax({
                            url: '/point-of-sale',
                            type: 'post',
                            data: {
                                'spa_id' : '{{$spa->id}}',
                                'amount_paid' : 0,
                                'payment_status' : 'pending',
                                'user_id' : '{{auth()->user()->id}}'
                            },
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        }).done((sales) => {
                            console.log(sales.sales.id)
                            if(sales.success === true)
                            {
                                Swal.fire('Hooray!', 'Sales Instance Successfully created!', 'success')
                                setTimeout(function(){
                                    window.location.replace('/point-of-sale/add-transaction/{{$spa->id}}/'+sales.sales.id);
                                },1000)

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
