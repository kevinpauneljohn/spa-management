function loadRoomAvailability(spa_id)
{
    filterPreSelectedRoom = [];
    $.ajax({
        'url' : '/receptionist-lists/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('.displayRoomData').html('');
        },
        success: function(result){
            $('.countSelected').text(0);
            if (result.length > 3) {
                $('#room-availability').addClass('overflow');
            } else {
                $('#room-availability').removeClass('overflow');
            }

            $('.displayRoomData').html('');
            $.each(result , function(index, val) {
                clearInterval(interValCountDown[val.room_id])
                var roomLink = '<a href="#" data-transaction_id="'+val.data.id+'" data-id="'+val.room_id+'" class="small-box-footer reservedInfo">More info <i class="fas fa-arrow-circle-right"></i></a>';
                var divAvailable = '';
                var divPointer = '';
                var isAvailable = 'no';
                var backgroundIcon = '<i class="fas fa-ban"></i>';
                if (val.data == '') {
                    // divAvailable = 'divClickable';
                    divAvailable = '';
                    divPointer = 'pointer_ ';
                    isAvailable = 'yes';
                    backgroundIcon = '<i class="fas fa-hot-tub"></i>';
                    roomLink = '<div class="small-box-footer isFooterAvailable'+val.room_id+'">Available <i class="fas fa-plus-circle"></i></div>';
                }

                var fullName = '';
                var startTime = '0';
                var endTime = '0';
                var roomTime = 0;
                if (val.data != '') {
                    fullName = val.data.client.firstname+' '+val.data.client.lastname;
                    startTime = val.data.start_time;
                    endTime = val.data.end_time;
                    roomTime = val.data.start_and_end_time;
                    filterPreSelectedRoom.push(val.room_id);
                }

                displayRoomData = '<div data-id="'+val.room_id+'" class="col-md-4 '+divAvailable+' '+divPointer+'" id="'+val.room_id+'">';
                    displayRoomData += '<input type="hidden" id="isAvailable'+val.room_id+'" value="'+isAvailable+'">';
                    displayRoomData += '<div class="parentAvailDiv'+val.room_id+' small-box '+val.is_color_set+'">';
                        displayRoomData += '<div class="inner">';
                            displayRoomData += '<h4 class="text-bold text-yellow">Room #: '+val.room_id+'</h4>';
                            displayRoomData += '<h6>Name: <b>'+fullName+'</b></h6>';
                            displayRoomData += '<h6>Time: <b>'+roomTime+'</b></h6>';
                            displayRoomData += '<h6>Remaining Time: <b><span id="countdown'+val.room_id+'"></span></b></h6>';
                        displayRoomData += '</div>';
                        // displayRoomData += '<div class="icon">';
                        //     displayRoomData += backgroundIcon;
                        // displayRoomData += '</div>';
                        displayRoomData += roomLink;

                    displayRoomData += '</div>';
                displayRoomData += '</div>';
                $( displayRoomData ).appendTo(".displayRoomData");

                if (endTime != 0) {
                    var interValCountDowns = setInterval(function() {
                        countdownInterval(val.room_id, val.data.start_time, val.data.end_time, spa_id)
                    }, 1000)

                    interValCountDown[val.room_id] = interValCountDowns;

                }
            });
        }
    });
}

var interValCountDown = [];
function countdownInterval(id, start_time, end_time, spa_id)
{
    console.log('test')
    var countDownStartDate = new Date(start_time).getTime();
    var countDownEndDate = new Date(end_time).getTime();
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
            clearInterval(interValCountDown)
            loadRoomAvailability(spa_id);
        }
    } else {
        $("#countdown"+id).text('Waiting...');
    }
}