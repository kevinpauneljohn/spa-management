<input type="hidden" class="form-control spaId" value="{{$spaId}}">
<div class="alert alert-primary alert-dismissible">
    <h5><i class="icon fas fa-info"></i> Note:</h5>
    Green color means available, Gray color means occupied.
</div>
<div class="row displayRoomData">

</div>
@push('css')
    <style>

    </style>
@endpush

@push('js')
    @if(auth()->check())
        <script src="{{asset('js/alerts.js')}}"></script>
        <script>
            $(document).ready(function(){
                    var spa_id = $('.spaId').val();

                    UnAvailableRoom = [];
                    $.ajax({
                        'url' : '/receptionist-lists/{{$spaId}}',
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
                                    UnAvailableRoom.push(val.room_id);
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
                                        countdownInterval(val.room_id, val.data.start_time, val.data.end_time)
                                    }, 1000)

                                    interValCountDown[val.room_id] = interValCountDowns;

                                }
                            });
                        }
                    });
            });
        </script>
    @endif
@endpush
