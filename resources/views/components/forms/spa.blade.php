
    <form class="spa-form">
        @csrf
        <x-adminlte-modal id="add-spa" title="Add New Spa" size="lg" theme="olive"
                           v-centered static-backdrop scrollable>
            <x-adminlte-input name="name" label="Name" placeholder="Spa name here"
                              fgroup-class="col-md-12 name" disable-feedback/>

            <x-adminlte-textarea name="address" label="Address" fgroup-class="col-md-12 address" placeholder="Address here"/>

            <x-adminlte-input name="number_of_rooms" label="Number Of Rooms" type="number"
                              igroup-size="sm" min=1 fgroup-class="col-md-12 number_of_rooms">
                <x-slot name="appendSlot">
                    <div class="input-group-text bg-dark">
                        <i class="fas fa-bed"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-select2 name="category" fgroup-class="col-md-12 category" label="Category">
                <option value="">-- Select --</option>
                <option value="salon">Salon</option>
                <option value="spa">Spa</option>
            </x-adminlte-select2>

            <x-slot name="footerSlot">
                <x-adminlte-button class="mr-auto" theme="default" label="Close" data-dismiss="modal"/>
                <x-adminlte-button type="submit" theme="success" label="Submit"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

