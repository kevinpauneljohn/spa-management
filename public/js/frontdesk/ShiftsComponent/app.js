$(document).on('click', '.btnStartShift', function(e) {
    e.preventDefault();
    startShiftPos($('#spa_id_val').val());
});

$(document).on('click', '.btnMoneyOnHand', function(e) {
    e.preventDefault();
    var money = $('#money_on_hand').val();
    var id = $('#start_shit_id').val();
    startShiftMoney(id, money);
});

$(document).on('click', '.btnEndShift', function() {
    var id = $('#start_shit_id').val();
    endShiftPost(id);
});

$(document).on('click', '.viewEndShiftReport', function (e) {
    e.preventDefault();
    loadEndOfShiftReport();
    $('#view-shift-report').modal('show');
    $('#start-shift-modal').modal('toggle');
});

$('#view-shift-report').on('hidden.bs.modal', function () {
    $('#start-shift-modal').modal('show');
    getPosShift($('#spa_id_val').val());
})