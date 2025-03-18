<div class="table-responsive">
    <table id="attendance-list" class="table table-bordered table-hover table-striped" role="grid" style="width:100%;">
        <thead>
        <tr role="row">
            @if(is_null($employee))
                <th>Name</th>
            @endif
            <th>Time In</th>
            <th>Time out</th>
            <th>Break In</th>
            <th>Break out</th>
            <th>Total Hours</th>
            <th>Total Break in Mins</th>
            <th>Total Hours Less Break</th>
            <th>Late in mins</th>
            <th>Allow Overtime</th>
            <th>Total OT (Hours)</th>
            <th>Updated At</th>
            <th>Updated by</th>
            <th>Action</th>
        </tr>
        </thead>
    </table>
</div>

<form id="attendance-form" class="attendance-saving-form">

    @csrf
    <x-adminlte-modal id="attendance-modal" title="Update Attendance" size="lg" theme="olive"
                      icon="fa fa-clock-o" v-centered static-backdrop scrollable>
        <div>
            @if(is_null($employee))
                <div class="row select-employee">
                    <div class="col-lg-6">
                        <label>Select Employee</label>
                        <select class="form-control" name="biometrics_user" id="biometrics_user">
                            <option value=""> -- Select Employee -- </option>
                            @foreach($employees as $biometric_user)
                                <option value="{{$biometric_user['biometrics_id']}}">{{$biometric_user['full_name']}} - {{$biometric_user['biometrics_id']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-6 time_in mt-2">
                    <label for="time_in">Time In</label>
                    <input type="datetime-local" name="time_in" class="form-control" id="time_in" step="any">
                </div>
                <div class="col-lg-6 time_out mt-2">
                    <label for="time_out">Time Out</label>
                    <input type="datetime-local" name="time_out" class="form-control" id="time_out" step="any">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 break_in mt-2">
                    <label for="break_in">Break In</label>
                    <input type="datetime-local" name="break_in" class="form-control" id="break_in" >
                </div>
                <div class="col-lg-6 break_out mt-2">
                    <label for="break_out">Break Out</label>
                    <input type="datetime-local" name="break_out" class="form-control" id="break_out">
                </div>
            </div>
        </div>
        <x-slot name="footerSlot">
            <x-adminlte-button theme="danger" class="mr-auto" label="Dismiss" data-dismiss="modal"/>
            <x-adminlte-button type="submit"  theme="success" label="Save"/>
        </x-slot>
    </x-adminlte-modal>
</form>

@push('js')
    @once
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
        </script>
    @endonce
    <script>
        $(document).ready(function(){
            $('#attendance-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '@if(is_null($employee)){!! route('all-employees-attendance') !!}@else {!! route('employee-attendance',['employee_id' => $employee->id]) !!} @endif',
                columns: [
                    @if(is_null($employee)){ data: 'name', name: 'name'}, @endif
                    { data: 'time_in', name: 'time_in'},
                    { data: 'time_out', name: 'time_out'},
                    { data: 'break_in', name: 'break_in'},
                    { data: 'break_out', name: 'break_out'},
                    { data: 'total_hours', name: 'total_hours'},
                    { data: 'total_break_in_minutes', name: 'total_break_in_minutes'},
                    { data: 'total_hours_less_break', name: 'total_hours_less_break'},
                    { data: 'late_in_minutes', name: 'late_in_minutes'},
                    { data: 'is_overtime_allowed', name: 'is_overtime_allowed'},
                    { data: 'total_overtime', name: 'total_overtime'},
                    { data: 'updated_at', name: 'updated_at'},
                    { data: 'user_id', name: 'user_id'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive:true,
                order:[1,'desc'],
                pageLength: 50
            })
        });
    </script>
    @once
        <script src="{{asset('/js/attendance/attendance.js')}}"></script>
    @endonce
@endpush
