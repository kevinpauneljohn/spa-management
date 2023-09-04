<button class="btn btn-primary btn-sm" id="end-shift-btn">End Shift</button>

@once
    @push('js')
        <script>
            $(document).on('click','#end-shift-btn',function(){
                Swal.fire({
                    title: 'End Shift?',
                    html: 'are you sure you want to end your shift?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.value === true) {

                        $.ajax({
                            url: '/end-shift/{{$spaId}}',
                            type: 'post',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend: function(){

                            }
                        }).done(function(response){
                            console.log(response);
                            if(response.success === true)
                            {
                                window.location.href = '/start-shift/{{$spaId}}?endShift=true'
                            }
                        }).always(function(){
                        });

                    }
                })
            });
        </script>
    @endpush
@endonce
