<div class="modal fade modal_add_permission" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('module.permission.type.save') }}" method="post" class="form-ajax" data-success="success_submit">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="modal-header">
                <h4 class="modal-title">@if($model->name) {{ $model->name }} @else {{trans('labutton.add_new')}}  @endif</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label> {{ trans('backend.name') }} <span class="text-danger">*</span></label>
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}">
                </div>

                <div class="form-group">
                    <label>{{trans('latraining.description')}}</label>
                    <textarea cols="15" class="form-control" name="description" rows="3">{{$model->description}}</textarea>
                </div>
                <div class="form-group">
                    <label>{{trans('backend.viewable_units')}} <span class="text-danger">*</span></label>
                    <input type="text" id="agentSearch" class="form-control" placeholder="Nhập tên đơn vị để tìm kiếm" title="Nhập tên đơn vị để tìm kiếm">
                    <div class="list-group checkbox-list-group wrapped_list" style="max-height: 200px;overflow: auto;" id="wrapped_list">
                        <div class="" id="results">
                        </div>
                        <div class="ajax-loading text-center m-3">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn"><i class="fa fa-save"></i> {{trans('labutton.save')}}</button>
                <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> {{trans('labutton.close')}}</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on("keydown", "form", function(event) {
        return event.key != "Enter";
    });

    $("#agentSearch").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            var id = $('input[name=id]').val() ? $('input[name=id]').val() : 0;
            var search = $(this).val();
            if (search) {
                $('.ajax-loading').show();
                $('#results').hide();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('module.permission.type.search_units') }}' + "?search=" + search + "&id=" + id,
                    dataType: 'html'
                }).done(function(data) {
                    $('.ajax-loading').hide();
                    $('#results').show();
                    $("#results").html(data);
                }).fail(function(data) {
                    item.html(oldtext);
                    show_message('{{ trans('laother.data_error') }}', 'error');
                    return false;
                });
            } else {
                console.log(2);
                $("#results").html('');
                load_unit_permission(1, id);
            }
        }
    });

    $("input[name=unit\\[\\]]").on('click', function () {
        var unit_id = $(this).val();

        if($(this).is(":checked")){
            $("input[name=type\\["+unit_id+"\\]]").filter("[value=owner]").prop('checked', true);
        }else if($(this).is(":not(:checked)")){
            $("input[name=type\\["+unit_id+"\\]]").prop('checked', false);
        }
    });

    var wrapped_list = document.getElementById('wrapped_list')
    wrapped_list.addEventListener('scroll', function(event)
    {
        var element = event.target;
        if (element.scrollHeight - element.scrollTop <= (element.clientHeight + 2))
        {
            var id = $('input[name=id]').val() ? $('input[name=id]').val() : 0;
            if(empty == 0) {
                page++;
                load_unit_permission(page, id);
            }
        }
    });
</script>
