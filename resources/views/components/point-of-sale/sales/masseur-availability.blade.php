
@foreach($spa->therapists as $therapist)

    @if(!$therapist->is_excluded)
        <span class="text-info text-bold">{{$therapist->full_name}}</span> <span class="text-danger text-bold progress-transaction-details" id="room-{{$therapist->id}}"></span>
        <span class="text-info progress-transaction-details" id="end-time-{{$therapist->id}}"></span>
        <div class="progress mb-3">
            <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar"
                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0" id="progress-{{$therapist->id}}">
                <span id="progress-indicator-{{$therapist->id}}">40% Complete (success)</span>
            </div>
        </div>
    @endif

@endforeach

@once
    @push('js')
        <script>

            $(document).ready(function(){
                therapistsAvailability()
            });

            const therapistsAvailability = () => {
                $.get('/display-therapist-availability-in-progress-bar/{{$spa->id}}', function(therapists){
                    // console.log(therapists)
                    $.each(therapists, function(key, value){
                        let totalTime = 0;

                        if(key.length > 0)
                        {
                            let plus_time = value[0].plus_time !== null
                                ? parseFloat(value[0].plus_time) : 0;

                            totalTime = parseFloat(value[0].service.duration) + plus_time
                            // console.log(totalTime)

                            let remaining_minutes = getMinutes(moment().format("YYYY-MM-DD, hh:mm:ss"), value[0].end_time_twelve_hour_format);
                            let percentage = (remaining_minutes / totalTime) * 100;

                            if(percentage > 30 && percentage < 80)
                            {
                                $('#progress-'+key).closest('.progress-bar').removeClass('bg-primary').addClass('bg-warning')
                            }
                            if(percentage >= 0 && percentage < 30)
                            {
                                $('#progress-'+key).closest('.progress-bar').removeClass('bg-warning').addClass('bg-danger')
                            }
                            $('#progress-'+key).css('width',parseFloat(percentage)+'%');
                            $('#progress-indicator-'+key).text(parseFloat(percentage).toFixed(2)+'%');
                            $('#room-'+key).text('/ #'+value[0].room_id);
                            $('#end-time-'+key).text('/ '+value[0].end_time_twelve_hour_format);
                        }else if(key.length === 0){
                            $('#room-'+key).text('');
                            $('#end-time-'+key).text('');
                        }
                    })
                });
            };

            const getMinutes = (start_time, end_time) => {
                let startTime = new Date(start_time);
                let endTime = new Date(end_time);
                let difference = endTime.getTime() - startTime.getTime(); // This will give difference in milliseconds
                return  Math.round(difference / 60000);
            }
        </script>
    @endpush
@endonce
