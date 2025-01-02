<form class="schedule-settings-form">
    @csrf
    <input type="hidden" name="employee_id" value="{{$employee->id}}">
    <div class="form-group mb-4 days_of_work">
        <div>
            <label for="days">Days Of Work</label><span class="required">*</span>
        </div>
        @foreach($days_of_work as $day)
            <input type="checkbox" name="days_of_work[]" class="mr-1" id="days_of_work" value="{{$day}}" @if($is_employee_have_saved_schedule && collect($employee->schedule_setting->days_of_work)->contains($day)) checked="checked" @endif/> <span class="mr-4">{{$day}}</span>
        @endforeach
    </div>
    <hr/>
    <div class="form-group schedule">
        <label for="schedule">Schedule</label><span class="required">*</span>
        <select class="form-control mb-2" name="schedule" id="schedule">
            <option value="">-- Select Schedule --</option>
            @foreach($schedules as $schedule)
                <option value="{{$schedule->id}}" @if($is_employee_have_saved_schedule && $employee->schedule_setting->schedule_id == $schedule->id) selected="selected" @endif>
                    {{ucwords($schedule->name).' / '}}
                    {{\Carbon\Carbon::parse($schedule->time_in)->format('g:i A').' - '}}
                    {{\Carbon\Carbon::parse($schedule->time_out)->format('g:i A')}}
                </option>
            @endforeach
        </select>
    </div>
    <hr class="mt-3"/>
    <button type="submit" class="btn btn-primary save-schedule-settings">Save</button>
</form>

@once
    @push('js')
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            let csrf_token = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
            let scheduleSettingsForm = $('.schedule-settings-form');

            $(document).on('submit','.schedule-settings-form', function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                $.ajax({
                    url: '{{route('schedule-settings.store')}}',
                    type: 'post',
                    data: data,
                    headers: csrf_token,
                    beforeSend: function(){
                        scheduleSettingsForm.find('.text-danger').remove();
                        scheduleSettingsForm.find('.save-schedule-settings').attr('disabled',true).text('Saving...');
                    }
                }).done(function(response){

                    if(response.success === true)
                    {
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        });
                    }else{
                        Toast.fire({
                            type: 'danger',
                            title: response.message
                        });
                    }

                }).fail(function(xhr, status, error){
                    console.log(xhr)

                    $.each(xhr.responseJSON.errors, function(key, value){
                        console.log(key)

                        scheduleSettingsForm.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    });

                }).always(function(){
                    scheduleSettingsForm.find('.save-schedule-settings').attr('disabled',false).text('Save');
                });
            });
        </script>
    @endpush
@endonce
