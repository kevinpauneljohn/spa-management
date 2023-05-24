function countdown(id, start_time, end_time)
{
    var countDownStartDate = new Date(start_time).getTime();
    var countDownEndDate = new Date(end_time).getTime();
    var x = setInterval(function() {
        var now = new Date().getTime();
        if (now >= countDownStartDate) {
            $("#countdown"+id).text('');
            var distance = countDownEndDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
            $("#countdown"+id).text(days + "d " + hours + "h "+ minutes + "m " + seconds + "s ");
    
            if (distance < 0) {
                clearInterval(x);
                loadRoom();
                getTotalSales($('#spa_id_val').val());
                getMasseurAvailability($('#spa_id_val').val());
                loadSales($('#spa_id_val').val());
                loadData($('#spa_id_val').val());
            }
        } else {
            $("#countdown"+id).text('Waiting...');
        }
    }, 1000);
}

function countdownModal(start_time, end_time)
{
    var countDownStartDate = new Date(start_time).getTime();
    var countDownEndDate = new Date(end_time).getTime();
    var x = setInterval(function() {
        var now = new Date().getTime();
        if (now >= countDownStartDate) {
            $(".viewRemainingTime").text('');
            var distance = countDownEndDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            $(".viewRemainingTime").text(days + "d " + hours + "h "+ minutes + "m " + seconds + "s ");
    
            if (distance < 0) {
                clearInterval(x);
                $(".viewRemainingTime").text('00:00');
            }
        } else {
            $(".viewRemainingTime").text('Waiting...');
        }
    }, 1000);
}

function countdownTherapist(id, start_time, end_time, total_seconds)
{
    var x = setInterval(function() {
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
            $('#countdownTherapistPercentage'+id).text('Waiting...');
        }
    }, 1000);
}

function countdownUpcoming(id, start_time, end_time, total_seconds)
{
    $('#countdownUpcomingPercentage'+id).text('');
    var x = setInterval(function() {
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
    }, 1000);
}