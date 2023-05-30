//format price with comma
function ReplaceNumberWithCommas(value) {
    var n= value.toString().split(".");
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    return n.join(".");
}

//remove object in array list
function removeValue(list, value) {
    list = list.split(',');
    list.splice(list.indexOf(value), 1);
    return list.join(',');
}

//Subtract hours from date and time
function subtractHours(date, hours)
{
    date.setHours(date.getHours() - hours);

    return date;
}

//Add hours from date and time
function addHours(date, hours)
{
    date.setHours(date.getHours() + hours);

    return date;
}

//Validate mobile number valid # 9612356789 - 10 digits without leading zero
function mobileValidation(mobileNo) 
{ 
    var regex = new RegExp("^[0-9\b]+$");
    var firstChar = mobileNo.slice(0,1);
    
    var status = false;
    if (mobileNo.length > 0) {
        if (mobileNo.length == 10 && firstChar > 0) {
            if (regex.test(mobileNo)) {
                return true;
            }
        }
    }
    
    return status;
} 

//format date and time
function getDatetime(val) {
    return getdate(val) + ' ' + gettime(val);
}
  
function getdate(val) {
    var y = val.substr(0, 4);
    var m = val.substr(5, 2);
    var d = val.substr(8, 2);
    return d + '/' + m + '/' + y;
}
  
function gettime(val) {
    return timeTo12HrFormat(val.substr(11, 5));
}
  
function timeTo12HrFormat(time) { // Take a time in 24 hour format and format it in 12 hour format
    var time_part_array = time.split(":");
    var ampm = 'AM';

    if (time_part_array[0] >= 12) {
        ampm = 'PM';
    }

    if (time_part_array[0] > 12) {
        time_part_array[0] = time_part_array[0] - 12;
    }

    formatted_time = time_part_array[0] + ':' + time_part_array[1] + ' ' + ampm;

    return formatted_time;
}

// Front End Validation
// value - Field value
// field - Field Name
// guest_id - For each function field+id, if single form default value 0
// validation_type - to get different type of field
function validateAppointmentForm(value, field, guest_id, validation_type) {
    var error = '';
    var date_add = new Date();
    var date_substract = new Date();
    var substractDate = subtractHours(date_substract, 1);
    var addDate = addHours(date_add, 1);
   
    if (guest_id > 0) {
        if (validation_type == 'length' && value.length < 1) {
            error = 'The Guest '+guest_id+' '+field+' field is required.';
        } else if (validation_type == 'nullable' && value == null) {
            error = 'The Guest '+guest_id+' '+field+' field is required.';
        } else if (validation_type == 'mobile' && !mobileValidation(value)) {
            error = 'The Guest '+guest_id+' '+field+' field is required and must be a number, have exactly ten digits, and not start with zero.';
        } else if (validation_type == 'checkbox' && value == 'no') {
            error = 'The Guest '+guest_id+' '+field+' checkbox field is required.';
        } else if (validation_type == 'time' && value.length < 1) {
            if (substractDate > new Date(value)) {
                error = 'The Guest '+guest_id+' '+field+' date and time must be less than 1 hour from the current time.';
            } else {
                error = 'The Guest '+guest_id+' '+field+' field is required.';
            }
        }
    }

    if (error != '') {
        toastr.error(error);
    }
}