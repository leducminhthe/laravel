<div role="main" id="rolepermission">
    <div class="row">
        <div class="col-md-8 form-inline">
            <form class="form-inline w-100" id="form-search">
                <input type="text" name="search" class="form-control mr-1" placeholder="{{ data_locale('Nhập mã / tên quyền','') }}">
                <div class="w-30 mr-1">
                    <select name="group" class="form-control select2" data-placeholder="{{ trans('lasetting.group') }}">
                        <option value=""></option>
                        <option value="1">{{ trans('larole.decentralization_manager') }}</option>
                        <option value="2">{{ trans('larole.organization_info_manager') }}</option>
                        <option value="3">{{ trans('lamenu.learning_manager') }}</option>
                        <option value="4">{{ trans('larole.exam_manager') }}</option>
                        <option value="5">{{ trans('larole.instructor_manager') }}</option>
                        <option value="6">{{ trans('lapromotion.promotion') }}</option>
                        <option value="7">{{ trans('lacategory.competition_program') }}</option>
                        <option value="8">{{ trans('larole.user_manager') }}</option>
                        <option value="9">{{ trans('larole.general') }}</option>
                        <option value="10">{{ trans('laforums.forum') }}</option>
                        <option value="11">{{ trans('lamenu.survey') }}</option>
                        <option value="12">{{ trans('larole.unit_training') }}</option>
                        <option value="13">{{ trans('larole.roadmap') }}</option>
                        <option value="14">{{ trans('larole.training_plan') }}</option>
                        <option value="15">{{ trans('larole.online_training') }}</option>
                        <option value="16">{{ trans('larole.offline_training') }}</option>
                        <option value="17">{{ trans('lamenu.library') }}</option>
                        <option value="18">{{ trans('larole.news_manager') }}</option>
                        <option value="19">{{ trans('lamenu.setting') }}</option>
                        <option value="20">{{ trans('lamenu.new_report') }}</option>
                        <option value="21">{{ trans('larole.virtual_course') }}</option>
                        <option value="22">Sales Kit</option>
                    </select>
                </div>
                <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
            </form>
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['role-edit', 'role-create'])
                <button  class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{trans('labutton.save')}}</button>
                @endcanany
                <a href="{{ route('backend.roles') }}" class="btn"><i class="fa fa-times-circle"></i> {{trans('labutton.cancel')}}</a>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <br>
    <form>
        <table id="table" class="tDefault table table-hover bootstrap-table"
            data-detail-view="true"
            data-detail-formatter="detailFormatter"
            data-page-list="[10, 50, 100, 200, 500]"
        >
            <thead>
            <tr>
                <th data-field="id" data-width="5%" class="text-center">#</th>
                <th data-field="name" data-width="20%">{{trans('backend.name')}}</th>
                <th data-field="description"data-width="30%">{{trans('latraining.description')}}</th>
                <th data-width="25%" data-field="id" data-formatter="permission_formatter" class="text-center">
                    {{trans('backend.permission_group')}}
                </th>
                <th data-field="group" data-width="20%" data-formatter="group_formatter" class="text-center">{{trans('lasetting.group')}}</th>
            </tr>
            </thead>
        </table>
    </form>
</div>
<script type="text/javascript">

    function index_formatter(value, row, index) {
        return (index+1);
    }

    function name_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="edit-item a-color" data-id="'+ row.id +'">'+ value +'</a>';
    }

    function group_formatter(value, row, index) {
        var text = '';
        var group = parseInt(row.group);
        switch (group) {
            case 1:
                text = '<span>{{ trans('larole.decentralization_manager') }}</span>'
                break;
            case 2:
                text = '<span>{{ trans('larole.organization_info_manager') }}</span>'
                break;
            case 3:
                text = '<span>{{ trans('lamenu.learning_manager') }}</span>'
                break;
            case 4:
                text = '<span>{{ trans('larole.exam_manager') }}</span>'
                break;
            case 5:
                text = '<span>{{ trans('larole.instructor_manager') }}</span>'
                break;
            case 6:
                text = '<span>{{ trans('lapromotion.promotion') }}</span>'
                break;
            case 7:
                text = '<span>{{ trans('lacategory.competition_program') }}</span>'
                break;
            case 8:
                text = '<span>{{ trans('larole.user_manager') }}</span>'
                break;
            case 9:
                text = '<span>{{ trans('larole.general') }}</span>'
                break;
            case 10:
                text = '<span>{{ trans('laforums.forum') }}</span>'
                break;
            case 11:
                text = '<span>{{ trans('lamenu.survey') }}</span>'
                break;
            case 12:
                text = '<span>{{ trans('larole.unit_training') }}</span>'
                break;
            case 13:
                text = '<span>{{ trans('larole.roadmap') }}</span>'
                break;
            case 14:
                text = '<span>{{ trans('larole.training_plan') }}</span>'
                break;
            case 15:
                text = '<span>{{ trans('larole.online_training') }}</span>'
                break;
            case 16:
                text = '<span>{{ trans('larole.offline_training') }}</span>'
                break;
            case 17:
                text = '<span>{{ trans('lamenu.library') }}</span>'
                break;
            case 18:
                text = '<span>{{ trans('larole.news_manager') }}</span>'
                break;
            case 19:
                text = '<span>{{ trans('lamenu.setting') }}</span>'
                break;
            case 20:
                text = '<span>{{ trans('lamenu.new_report') }}</span>'
                break;
            case 21:
                text = '<span>{{ trans('larole.virtual_course') }}</span>'
                break;
            case 22:
                text = '<span>Sales Kit</span>'
                break;
        }
        return text;
    }

    function detailFormatter(index, row) {
        var html = [], str ='';
        $.each(row.permission,function (i,e){
            var checked = (e.id==e.permission_id)?'checked':'';
            str+='<div class="col-md-12 ml-lg-4"><label><input type="checkbox" '+checked+' onclick="selectPermission('+ row.id +','+ row.group_permission +')" class="btnselect btnselect_'+ row.id +'" name="btSelectItem" value="'+e.id+'"> '+e.description+':</label></div>';
        });
        html.push(`<label><input type="checkbox" name="`+row.name+`" class="select_all check_all_`+ row.id +`" onclick="selectAll(`+row.id+`,`+ row.group_permission +`)"/> {{ trans("backend.select_all") }} </label>
                    <div class="row ml-lg-8">
                        <div class="col-md-8 ` + row.name + `" >` + str + `</div>
                    </div>`
                );
        return html.join('')
    }

    function permission_formatter(value, row, index) {
        var html ='<select class="form-control" id="group_permission_'+ row.id +'" name="group-permission['+row.id+']"><option value="0">--{{ trans("backend.permission_group") }}--</option>';
        $.each(row.permission_type,function (i,e) {
            var select = e.id==row.permission_type_id?'selected':'';
            if (e.id==row.group_permission)
                html+='<option '+select+' value='+e.id+'>'+e.name+'</option>';
        });
        html+="</select>";
        return html;
    }
    var ajax_save = "{{ route('backend.roles.ajax_save', ['role' => $role->id]) }}";
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('backend.roles.getpermission',['role'=>$role->id]) }}'
    });

    $('#table').on('post-body.bs.table', function (e, d) {
        $("#table").bootstrapTable("expandAllRows");
    });

    function selectAll(id, value){
        if ( $('.check_all_' + id).prop('checked') == true) {
            $(".btnselect_" + id).prop("checked", true);
            $('#group_permission_'+ id).val(value);
        } else {
            $(".btnselect_" + id).prop("checked", false);
            $('#group_permission_'+ id).val(0);
        }
    }

    function selectPermission(id, value) {
        var checked = 0;
        $(".btnselect_" + id).each(function(){
            if ($(this).prop('checked') == true){
                checked = 1;
            }
        });
        if(checked == 1) {
            $('#group_permission_'+ id).val(value);
        } else {
            $('#group_permission_'+ id).val(0);
        }
    }
</script>
