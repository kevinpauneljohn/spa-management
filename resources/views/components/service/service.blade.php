<form role="form" id="service-form" class="form-submit">
    @csrf
    <input type="hidden" name="spaId" class="spa-id" value="{{$spa->id}}">
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
                            <button type="button" class="btn btn-default closeModal" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary info_next_btn" onclick="addServiceStepper.next()" disabled>Next</button>
                        </div>
                        <div id="price-part" class="content" role="tabpanel" aria-labelledby="price-part-trigger">
                            <div class="form-group duration">
                                <label for="duration">Duration</label> <i>(minutes)</i><span class="required">*</span>
                                <br />
                                <select class="form-control duration-select" name="duration" id="duration" style="width:100%;">
                                    <option value="">Select here</option>
                                    @foreach($range as $key => $data)
                                        <option value="{{$data}}">{{$data}} minutes</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group price">
                                <label for="price">Price</label>
                                <input type="number" class="form-control" id="price" name="price">
                            </div>
                            <div class="form-group category">
                                <label for="category">Category</label>
                                <select name="category" class="form-control" id="category">
                                    <option value="">Select here</option>
                                    <option value="regular">Regular</option>
                                    <option value="promo">Promo</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-default price_previous_btn hiddenBtn" onclick="addServiceStepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary price_submit_btn add-service-btn hiddenBtn" disabled>Submit</button>
                        </div>
                    </div>
                </div>

</form>
