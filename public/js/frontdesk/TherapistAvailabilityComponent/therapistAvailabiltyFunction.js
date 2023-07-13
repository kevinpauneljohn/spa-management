function getMasseurAvailability(spa_id)
{
    filterPreSelectedTherapist = [];
    $.ajax({
        'url' : '/transaction-masseur-availability/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            $('.availableMasseur').html('');
            clearInterval(interValTherpist)
            $.each(result, function (key, value) {
                var names;
                clearInterval(interValTherpist[value.id])
                if (value.data != '') {
                    filterPreSelectedTherapist.push(value.id);
                    var interValTherpists = setInterval(function() {
                        countdownTherapistInterval(value.id, value.data.start_time, value.data.end_time, value.data.total_seconds)
                    }, 1000)

                    interValTherpist[value.id] = interValTherpists;

                    names = value.firstname+' '+value.lastname+' <small class="font-weight-bold text-danger">[ Room # '+value.data.room_id+' ]</small>';
                } else {
                    names = value.firstname+' '+value.lastname;
                }

                var availableMasseur = '<span class="masseurName">'+names+'</span>';
                availableMasseur += '<div class="progress progress-xl">';
                    availableMasseur += '<div id="progressBarCalc'+value.id+'" class="progress-bar bg-info progress-bar-striped progress-bar-animated rounded-pill progressBarCalc" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>';
                    availableMasseur += '<span class="countdownTherapistPercentage" id="countdownTherapistPercentage'+value.id+'">Available</span>';
                availableMasseur += '</div>';

                $( availableMasseur ).appendTo(".availableMasseur");
            });
        }
    });
}

var interValTherpist = [];
function countdownTherapistInterval(id, start_time, end_time, total_seconds)
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
        $('#countdownTherapistPercentage'+id).text('');
        $('#progressBarCalc'+id).css('width', percentage+'%');
        $('#countdownTherapistPercentage'+id).text(percentage_text+'%');
    } else {
        $('#countdownTherapistPercentage'+id).text('Available...');
    }
}