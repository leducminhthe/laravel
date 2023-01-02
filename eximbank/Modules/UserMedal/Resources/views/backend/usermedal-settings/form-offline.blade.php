<p class="clearfix">
    @if(!$ro)
    <button id="offlinebtn" type="button" class="btn float-right" data-toggle="modal" data-target="#modal-offline">
        <i class="fa fa-plus"></i> {{ trans('lamenu.add_offline_course') }}
    </button>
    @endif
</p>
<div class="row">
	<div class="col-md-12">
		<table class="table border">
			<thead>
                <tr>
                    <th class="border" style="width:50px; text-align:center;">#</th>
                    <th class="border" style="width:150px;">{{ trans('latraining.course_code') }}</th>
                    <th class="border" style="text-align: left;">{{ trans('latraining.course_name') }}</th>
                    <th class="border" style="width:320px;text-align: left;">{{ trans('latraining.time_complete') }}</th>
                    <th class="border" style="width:150px; text-align:center;">{{ trans('labutton.task') }}</th>
                </tr>
			</thead>
			<tbody>
            @if($offline_items)
                @foreach($offline_items as $index => $v)
                    <tr>
                        <td class="border" style="text-align:center;">{{ $loop->index + 1 }}</td>
                        <td class="border">{{ $v->code }}</td>
                        <td class="border">{{ $v->name }}</td>
                        <td class="border">{{ date('d/m/Y H:i', $v->start_date)}} <i class="fas fa-long-arrow-alt-right"></i> {{ date('d/m/Y H:i', $v->end_date) }}</td>
                        <td class="border" style="text-align:center;">
                            @if(!$ro)
                            <a data-type="offline" href="javascript:void(0)" class="text-primary edit-setting" data-id="{{ $v->id }}"><i class="fa fa-1x fa-edit"></i></a>
                            <a href="javascript:void(0)" class="text-danger remove-setting" data-id="{{ $v->id }}"><i class="fa fa-1x fa-trash"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
			</tbody>
		</table>
	</div>
</div>

 <div class="modal fade" id="modal-offline" aria-labelledby="ModalOffline" aria-hidden="true" data-backdrop="static" data-keyboard="false">
     <form action="{{ route('module.usermedal-setting.save-item',['form'=>'3','type'=>$model->id]) }}" method="post" class="form-ajax">
         <input type="hidden" name="settingitem_id" value="">
         <div class="modal-dialog" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="ModalOffline">{{ trans('lamenu.offline_course') }}</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
                     </button>
                 </div>
                 <div class="modal-body">
                     <div class="row">
                         <div class="col-md-6"><div class="form-group">
                                 <label>{{ trans('latraining.from_date') }}</label>
                                 <input type="text" name="start_date" autocomplete="off" placeholder="{{ trans('latraining.from_date') }}" value="" class="form-control datepicker">
                             </div>
                             <div class="start_date_error text-danger"></div>
                         </div>

                         <div class="col-md-3">
                             <div class="form-group">
                                 <label>{{ trans('latraining.hour') }}</label>
                                 <select name="start_hour" class="form-control">
                                     @for($i=0;$i<=23;$i++)
                                     <option value="{{sprintf('%02d',$i)}}">{{sprintf('%02d',$i)}}</option>
                                   @endfor
                                 </select>
                             </div>
                         </div>

                         <div class="col-md-3">
                             <div class="form-group">
                                 <label>{{ trans('latraining.minute') }}</label>
                                 <select name="start_minute" class="form-control">
                                     @for($i=0;$i<=59;$i++)
                                     <option value="{{sprintf('%02d',$i)}}">{{sprintf('%02d',$i)}}</option>
                                     @endfor
                                 </select>
                             </div>
                         </div>

                     </div>

                     <div class="row">

                         <div class="col-md-6"><div class="form-group">
                                 <label>{{ trans('latraining.to_date') }}</label>
                                 <input type="text" name="end_date" autocomplete="off" placeholder="{{ trans('latraining.to_date') }}" value="" class="form-control datepicker">
                             </div>
                             <div class="end_date_error text-danger"></div>
                         </div>
                         <div class="col-md-3">
                             <div class="form-group">
                                 <label>{{ trans('latraining.hour') }}</label>
                                 <select id="endhour" name="end_hour" class="form-control">
                                     @for($i=0;$i<=23;$i++)
                                     <option value="{{sprintf('%02d',$i)}}">{{sprintf('%02d',$i)}}</option>
                                     @endfor
                                 </select>
                             </div>
                         </div>

                         <div class="col-md-3">
                             <div class="form-group">
                                 <label>{{ trans('latraining.minute') }}</label>
                                 <select name="end_minute" class="form-control">
                                     @for($i=0;$i<=59;$i++)
                                     <option value="{{sprintf('%02d',$i)}}">{{sprintf('%02d',$i)}}</option>
                                     @endfor
                                 </select>
                             </div>
                         </div>
                     </div>

                     <div class="row">
                         <div class="col-md-6">
                             <label>{{ trans('latraining.subject') }}</label>
                             <select name="subject" id="subject_offline" data-form="2" data-model="{{$model->id}}" class="form-control subject">
                                 <option value="0">{{ trans('latraining.subject') }}</option>
                                 @if ($subjects)
                                 @foreach ($subjects as $subject)
                                 <option value="{{$subject->id}}">{{$subject->code}} {{$subject->name}}</option>
                                 @endforeach
                                     @endif
                             </select>
                         </div>
                         <div class="col-md-6">
                             <div class="form-group">
                                 <label>{{ trans('lamenu.course') }}</label>
                                 <select class="form-control select2" name="item_id" id="sel_offline" >
                                     <option value="0">{{ trans('lamenu.course') }}</option>
                                 </select>
                             </div></div>
                     </div>

                     <span id="error_dd" style="font-size:11px; color:red;"></span>
                 </div>

                 <div class="modal-footer">
                     <button type="submit" class="btn"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                     <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                 </div>
             </div>
         </div>
     </form>
 </div>
