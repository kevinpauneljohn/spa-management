function getUpcomingGuest(spa_id)
{
    // UnAvailableGuest = [];
    $.ajax({
        'url' : '/appointment-upcoming/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('.upcomingGuest').html('');
        },
        success: function(result){
            $.each(result, function (key, value) {
                clearInterval(interValUpcoming[value.id])
                // UnAvailableGuest.push(value.id);
                var interValUpcomings = setInterval(function() {
                    countdownUpcomingInterval(value.id, value.created_at, value.start_time, value.total_seconds)
                }, 1000)

                interValUpcoming[value.id] = interValUpcomings;
                var names = value.fullname+' <small class="font-weight-bold text-danger">[ Mobile # '+value.mobile_number+' ]</small>';
                var upcomingGuest = '<span class="masseurName">'+names+'</span>';

                upcomingGuest += '<div class="progress progress-xl">';
                    upcomingGuest += '<div id="progressBarCalcUpcoming'+value.id+'" class="progress-bar bg-danger progress-bar-striped progress-bar-animated rounded-pill" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>';
                    upcomingGuest += '<span id="countdownUpcomingPercentage'+value.id+'">Upcoming</span>';
                upcomingGuest += '</div>';

                $( upcomingGuest ).appendTo(".upcomingGuest");
            });
        }
    });
}

var interValUpcoming = [];
function countdownUpcomingInterval(id, start_time, end_time, total_seconds)
{
    var progress_end_time = new Date(end_time);
    var progress_new_time = new Date();
    var progress_remaining_seconds = Math.floor(progress_end_time.getTime() - progress_new_time.getTime())/1000;
    var progress_seconds_parse = parseInt(progress_remaining_seconds);
    var progress_percentage = progress_seconds_parse / total_seconds * 100;
    var width_percentage = progress_percentage.toFixed(2);
    var percentage = 100 - width_percentage;
    var percentage_text = percentage.toFixed(2);

    
    if (percentage <= 100 && percentage > 0) {
        $('#countdownUpcomingPercentage'+id).text('');
        $('#progressBarCalcUpcoming'+id).css('width', percentage+'%');
        $('#countdownUpcomingPercentage'+id).text(percentage_text+'%');
    } else {
        $('#progressBarCalcUpcoming'+id).css('width', '100%');
        $('#countdownUpcomingPercentage'+id).text('Waiting to process the appointment...');
        $('#countdownUpcomingPercentage'+id).css('color', 'white');
    }
}
