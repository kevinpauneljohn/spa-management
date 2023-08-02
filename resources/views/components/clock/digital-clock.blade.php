<h3 class="clock text-info" id="clock"> </h3>


@once
    @push('js')
        @if(auth()->check())
            <script>

                $(document).ready(function(){
                    digitalClock();
                })

                function digitalClock()
                {
                    let time = moment().format("MM/DD/YYYY ddd, hh:mm:ss a")
                    $('#clock').html(time);
                    //to change time in every seconds
                    setTimeout( digitalClock, 1000 );
                }

            </script>
        @endif
    @endpush
@endonce
