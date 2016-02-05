@section('css_assets')
<link href="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
@stop

@section('js_assets')
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
@stop

@section('js_section')
<script>
    $(".tanggalan").datepicker();

    $(".number").inputmask("integer", {
        min: 1,
        onUnMask: function(maskedValue, unmaskedValue) {
            return unmaskedValue;
        },
    }).css('text-align', 'left');

    $("#account_id").change(function(){
        var _account_id = $(this).val();
        LoadRelationElement(_account_id);
    });

    function LoadRelationElement(accountId, selectedValue){
        var params = { account_id : accountId };
        if( typeof selectedValue != 'undefined' ){
            params.selected = selectedValue;
        }

        $.ajax({
            url: "{{ url('/ajax/account/check') }}",
            type: "GET",
            data: params,
            success: function(res){
                $("#relation-element").html(res);
            }
        });
    }

    @if( old('account_id') )
        LoadRelationElement("{{ old('account_id') }}", "{{ old('relation_id') }}");
    @else
        @if(isset($accountSaldo))
            var account_id  = "{{ $accountSaldo->account_id }}";
            var relation_id = "{{ $accountSaldo->relation_id }}";
            LoadRelationElement(account_id, relation_id);
        @else
            var selected = "{{ ( count($accounts) ? array_keys($accounts->toArray())[0] : '' ) }}";
            LoadRelationElement(selected);
        @endif
    @endif
</script>
@stop
