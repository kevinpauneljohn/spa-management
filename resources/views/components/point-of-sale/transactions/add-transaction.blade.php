@if(!$display)
<button type="button" class="btn bg-gradient-info" id="add-client-btn">Add Transaction</button>


    @once
        @push('js')
            <script>
                let clientModal = $('#add-client-modal');

                $(document).on('click','#add-client-btn', function(){
                    clientModal.modal('toggle');
                    clientModal.find('.modal-title').text('Add Transaction');
                });
            </script>
        @endpush
    @endonce
@endif
