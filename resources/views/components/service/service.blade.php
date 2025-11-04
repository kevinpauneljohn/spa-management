<form role="form" id="service-form" class="add-service">
    @csrf
    <input type="hidden" name="spa_id" class="spa-id" value="{{$spa->id}}">
                <div class="bs-stepper" id="bs-stepper-add">
                    <div class="bs-stepper-header" role="tablist">
                        <div class="step" data-target="#info-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="info-part" id="info-part-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Info</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#price-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="price-part" id="price-part-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Pricing</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <div id="info-part" class="content" role="tabpanel" aria-labelledby="info-part-trigger">
                            <div class="form-group name">
                                <label for="name">Name</label><span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label><span class="required">*</span>
                                <textarea name="description" class="form-control" id="description"></textarea>
                            </div>
                            <div class="form-group multiple_masseur">
                                <input type="checkbox" name="multiple_masseur" class="form-check-input" id="multiple_masseur" style="margin-left: auto !important" value="true"/> <label for="multiple_masseur" class="ml-4">Multiple masseur?</label>
                            </div>
                            <button type="button" class="btn btn-default closeModal" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary info_next_btn" onclick="addServiceStepper.next()">Next</button>
                        </div>
                        <div id="price-part" class="content" role="tabpanel" aria-labelledby="price-part-trigger">
                            <div class="form-group duration">
                                <label for="duration">Duration</label> <i>(minutes)</i><span class="required">*</span>
                                <br />
                                <select class="form-control" name="duration" id="duration" style="width:100%;">
                                    <option value="">Select here</option>
                                    @for($minutes = 5; $minutes <= 300; $minutes++)
                                        <option value="{{$minutes}}">{{$minutes}} minutes</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group price">
                                <label for="price">Price</label>
                                <input type="number" class="form-control" id="price" name="price">
                            </div>
                            <div class="form-group commission_reference_amount">
                                <label for="commission_reference_amount">Commission Reference Amount</label>
                                <input type="number" class="form-control" id="commission_reference_amount" name="commission_reference_amount" step="any">
                            </div>
{{--                            <div class="form-group price_per_plus_time">--}}
{{--                                <label for="price_per_plus_time">Plus time price every 15 minutes</label>--}}
{{--                                <input type="number" class="form-control" id="price_per_plus_time" name="price_per_plus_time">--}}
{{--                            </div>--}}
                            <div class="form-group category">
                                <label for="category">Category</label>
                                <select name="category" class="form-control" id="category">
                                    <option value="">Select here</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->category}}">{{$category->category}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-default price_previous_btn" onclick="addServiceStepper.previous()">Previous</button>
                            <button type="submit" class="btn btn-primary price_submit_btn add-service-btn">Submit</button>
                        </div>
                    </div>
                </div>

</form>

@once
    @push('js')
        <script>
            let serviceTable = $('#service-list');
            document.addEventListener('DOMContentLoaded', function () {
                window.addServiceStepper = new Stepper(document.querySelector('#bs-stepper-add'))
            });
        </script>
        <script>
            $(document).on('submit','.add-service',function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                $('#service-form').find('.is-invalid').removeClass('is-invalid');
                $('#service-form').find('.text-danger').remove();

                $.ajax({
                    'url' : '/service',
                    'type' : 'POST',
                    'data': data,
                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function () {
                        $('#service-form').find('.add-service-btn').val('Saving ... ').attr('disabled',true);
                    },success: function (result) {
                        console.log(result);
                        if(result.status) {
                            $('#service-form').trigger('reset');
                            $('#duration').val('').trigger('change');
                            serviceTable.DataTable().ajax.reload(null, false);

                            swal.fire("Done!", result.message, "success");
                            $('#add-new-service-modal').modal('toggle');
                            addServiceStepper.reset();

                        } else {
                            $.each(result, function (key, value) {
                                $(document).find('#'+key).addClass('is-invalid').after('<p class="text-danger">'+value+'</p>')
                            });
                        }

                        $('#service-form').find('.add-service-btn').val('Save').attr('disabled',false);
                    },error: function(xhr, status, error){
                        console.log(xhr);
                    }
                });
            });


            let serviceId;
            $(document).on('click','.edit-service-btn', function(){
                $('#service-form').find('.is-invalid').removeClass('is-invalid');
                $('#service-form').find('.text-danger').remove();
                $('#add-new-service-modal').modal('toggle');
                $('#add-new-service-modal').find('.modal-title').text('Edit Service Form')
                $('#service-form').removeClass('add-service').addClass('edit-service')
                addServiceStepper.reset();
                serviceId = this.id;

                $.ajax({
                    url: '/service/'+serviceId,
                    dataType: 'json',
                    beforeSend: function(){
                        $('.modal').find('.modal-content').append(overlay)
                    },
                }).done(function(data){
                    if(data.multiple_masseur == true)
                    {
                        $('#service-form').find('#multiple_masseur').prop('checked',true);
                    }
                    $.each(data, function(key, value){
                        $('#service-form').find('input[name='+key+'], textarea[name='+key+'], select[name='+key+']').val(value)
                    })
                }).always(function(){
                    $('.overlay').remove();
                });
            });

            $(document).on('submit','.edit-service',function(form){
                $('#service-form').find('.is-invalid').removeClass('is-invalid');
                $('#service-form').find('.text-danger').remove();
                form.preventDefault();
                let data = $(this).serializeArray();

                $.ajax({
                    url: '/service/'+serviceId,
                    type: 'PUT',
                    data: data,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function(){
                        $('.modal').find('.modal-content').append(overlay)
                    }
                }).done(function(result){

                    if(result.status === true) {
                        serviceTable.DataTable().ajax.reload(null, false);

                        // swal.fire("Done!", result.message, "success");
                        Toast.fire({
                            type: 'success',
                            title: result.message
                        });

                    }
                    else if(result.status === false)
                    {
                        Toast.fire({
                            type: 'warning',
                            title: result.message
                        });
                    }
                    else {
                        $.each(result, function (key, value) {
                            $(document).find('#'+key).addClass('is-invalid').after('<p class="text-danger">'+value+'</p>')
                        });
                    }
                }).fail(function(xhr, status, error){
                    console.log(xhr)
                }).always(function(){
                    $('.overlay').remove();
                });
            });

            $(document).on('click','.delete-service-btn',function(){
                $tr = $(this).closest('tr');
                id = this.id;
                let data = $tr.children('td').map(function () {
                    return $(this).text();
                }).get();

                swal.fire({
                    title: "Are you sure you want to delete Services: "+data[1]+"?",
                    icon: 'question',
                    text: "Please ensure and then confirm!",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Yes!",
                    cancelButtonText: "No!",
                    reverseButtons: !0
                }).then(function (e) {
                    if (e.value === true) {
                        $.ajax({
                            'url' : '/service/'+id,
                            'type' : 'DELETE',
                            'data': {},
                            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend: function () {
                                $('#delete-service-form').find('.delete-service-modal-btn').val('Deleting ... ').attr('disabled',true);
                            },success: function (result) {
                                if(result.status) {
                                    serviceTable.DataTable().ajax.reload(null, false);

                                    swal.fire("Done!", result.message, "success");
                                    $('#delete-service-modal').modal('hide');
                                } else {
                                    swal.fire("Warning!", result.message, "warning");
                                }

                                $('#delete-service-form').find('.delete-service-modal-btn').val('Delete').attr('disabled',false);
                            },error: function(xhr, status, error){
                                console.log(xhr);
                            }
                        });
                    } else {
                        e.dismiss;
                    }
                });
            });
        </script>
    @endpush
@endonce
