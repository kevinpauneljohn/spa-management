<form class="{{(!empty($therapist)) ? 'edit-therapist-form' : 'therapist-form'}}">
    @csrf
    @if((!empty($therapist)))
        <x-adminlte-input type="hidden" name="therapistId" value="{{$therapist->id}}"/>
        @endif
    @if(isset($spaId) && !empty($spaId))
        <input type="hidden" name="spa_id" value="{{$spaId}}">
    @endif
    <div class="bs-stepper" id="bs-stepper-add-therapist">
        <div class="bs-stepper-header" role="tablist">
            <div class="step" data-target="#name-part">
                <button type="button" class="step-trigger" role="tab" aria-controls="name-part" id="name-part-trigger">
                    <span class="bs-stepper-circle">1</span>
                    <span class="bs-stepper-label">Name</span>
                </button>
            </div>
            <div class="line"></div>
            <div class="step" data-target="#info-part">
                <button type="button" class="step-trigger" role="tab" aria-controls="info-part" id="info-part-trigger">
                    <span class="bs-stepper-circle">2</span>
                    <span class="bs-stepper-label">Info</span>
                </button>
            </div>
            <div class="line"></div>
            <div class="step" data-target="#contact-part">
                <button type="button" class="step-trigger" role="tab" aria-controls="contact-part" id="contact-part-trigger">
                    <span class="bs-stepper-circle">3</span>
                    <span class="bs-stepper-label">Contact</span>
                </button>
            </div>
            <div class="line"></div>
            <div class="step" data-target="#offer-part">
                <button type="button" class="step-trigger" role="tab" aria-controls="offer-part" id="offer-part-trigger">
                    <span class="bs-stepper-circle">4</span>
                    <span class="bs-stepper-label">Offer</span>
                </button>
            </div>
        </div>
        <div class="bs-stepper-content">
            <div id="name-part" class="content" role="tabpanel" aria-labelledby="name-part-trigger">
                <x-adminlte-input type="text" name="firstname" label="First Name" id="firstname" fgroup-class="firstname col-md-12" value="{{(!empty($therapist)) ? $therapist->user->firstname : ''}}"/>
                <x-adminlte-input type="text" name="middlename" label="Middle Name" id="middlename" fgroup-class="middlename col-md-12"  value="{{(!empty($therapist)) ? $therapist->user->middlename : ''}}"/>
                <x-adminlte-input type="text" name="lastname" label="last Name" id="lastname" fgroup-class="lastname col-md-12"  value="{{(!empty($therapist)) ? $therapist->user->lastname : ''}}"/>


                <x-adminlte-button class="therapist_closeModal" theme="default" label="Close" data-dismiss="modal"/>
                <x-adminlte-button class="therapist_name_next_btn float-right" theme="info" label="Next" onclick="addTherapistStepper.next()"/>
            </div>
            <div id="info-part" class="content" role="tabpanel" aria-labelledby="info-part-trigger">

                <x-adminlte-input type="date" name="date_of_birth" label="Date of Birth" fgroup-class="date_of_birth col-md-12"  value="{{(!empty($therapist)) ? $therapist->user->date_of_birth : ''}}"/>

                <div class="form-group gender col-md-12">
                    <span class="required">*</span><label for="gender">Gender</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value="">Select here</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <x-adminlte-select name="certificate" id="certificate" label="Certificate" fgroup-class="certificate col-md-12">
                    <option value="">Select here</option>
                    <option value="DOH">DOH</option>
                    <option value="NC2">NC2</option>
                </x-adminlte-select>

                <x-adminlte-button class="therapist_info_previous_btn" theme="default" label="Previous" onclick="addTherapistStepper.previous()"/>
                <x-adminlte-button class="therapist_name_next_btn float-right" theme="info" label="Next" onclick="addTherapistStepper.next()"/>
            </div>
            <div id="contact-part" class="content" role="tabpanel" aria-labelledby="contact-part-trigger">
                <div class="form-group mobile_number"><span class="required">*</span>
                    <label for="mobile_number">Mobile Number</label>
                    <input type="text" name="mobile_number" id="mobile_number" class="form-control" value="{{(!empty($therapist)) ? $therapist->user->mobile_number : ''}}">
                </div>

                <div class="form-group email">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{(!empty($therapist)) ? $therapist->user->email : ''}}">
                </div>
                <x-adminlte-button class="therapist_contact_previous_btn" theme="default" label="Previous" onclick="addTherapistStepper.previous()"/>
                <x-adminlte-button class="therapist_contact_next_btn float-right" theme="info" label="Next" onclick="addTherapistStepper.next()"/>
            </div>
            <div id="offer-part" class="content" role="tabpanel" aria-labelledby="offer-part-trigger">
                @if(empty($spaId))
                    <x-adminlte-select name="spa_id" fgroup-class="offer_type col-md-12" label="Select Spa">
                        <x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']" disabled="1"
                                            placeholder="Select an option..."/>
                    </x-adminlte-select>
                @endif

                <x-adminlte-select name="offer_type" id="offer_type" label="Offer Type" fgroup-class="offer_type col-md-12">
                    <option value="">Select here</option>
                    <option value="percentage_only">Percentage Only</option>
                    <option value="percentage_plus_allowance">Percentage + Allowance</option>
                    <option value="amount_only">Amount Only</option>
                    <option value="amount_plus_allowance">Amount + Allowance</option>
                    <option value="per_service_plus_allowance">Per Service + Allowance</option>
                    <option value="per_service_only">Per Service Only</option>
                </x-adminlte-select>


                <x-adminlte-input type="number" name="commission_percentage" label="Commission Rate" fgroup-class="commission_percentage" id="commission_percentage" value="{{(!empty($therapist)) ? $therapist->commission_percentage : ''}}"/>
                <x-adminlte-input type="number" name="commission_flat" label="Commission Amount" fgroup-class="commission_flat" id="commission_flat" value="{{(!empty($therapist)) ? $therapist->commission_flat : ''}}"/>
                <x-adminlte-input type="number" name="allowance" label="Allowance" fgroup-class="allowance" id="allowance" value="{{(!empty($therapist)) ? $therapist->allowance : ''}}"/>



                <x-adminlte-button class="therapist_offer_previous_btn" theme="default" label="Previous" onclick="addTherapistStepper.previous()"/>
                <x-adminlte-button type="submit" class="therapist_offer_submit_btn add-therapist-btn float-right" theme="info" label="Save"/>
            </div>
        </div>
    </div>
</form>
@once
    @push('js')
        <script>
            $("#gender").val("{{(!empty($therapist)) ? $therapist->gender : ''}}").change();
            $("#certificate").val("{{(!empty($therapist)) ? $therapist->certificate : ''}}").change();
            $("#offer_type").val("{{(!empty($therapist)) ? $therapist->offer_type : ''}}").change();
        </script>
    @endpush
@endonce
@section('plugins.BsStepper',true)
@section('plugins.BsStepper',true)
@section('plugins.ClearErrors',true)
@section('plugins.Toastr',true)
@section('plugins.CustomAlert',true)
@section('plugins.Therapist',true)
