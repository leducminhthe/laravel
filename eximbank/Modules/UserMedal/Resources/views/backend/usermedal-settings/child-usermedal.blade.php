
<h3>{{ trans('lamenu.badge_list') }}</h3>
<div class="row">
	<div class="col-md-12">
		<table class="table border">
			<thead>
                <tr>
                    <th class="border" style="width:50px; text-align: center;">#</th>
                    <th class="border" style="width:150px; text-align: center;">{{ trans('lacategory.image') }}</th>
                    <th class="border" style="width:120px; text-align: left;">{{ trans('lamenu.badge_code') }}</th>
                    <th class="border" style="text-align: left;">{{ trans('lamenu.badge_name') }}</th>
                    <th class="border" style="width:10px; text-align: center;">{{ trans('lacategory.rank') }}</th>
                    <th class="border" style="width:150px; text-align: center;">{{ trans('lamenu.from_point') }}</th>
                    <th class="border" style="width:150px; text-align: center;">{{ trans('lamenu.to_point') }}</th>
                </tr>
			</thead>
			<tbody>
            @if($submedal)
                @foreach($submedal as $index => $v)
                    <tr>
                        <td  class="border" style="text-align: center;">{{ $loop->index + 1 }}</td>
                        <td class="border" style="text-align: center;">
                            <img src="{{ image_file($v->photo) }}" alt="" style="height: 100px; width: auto;">
                        </td>
                        <td class="border">{{ $v->code }}</td>
                        <td class="border">{{ $v->name }}</td>
                        <td class="border" style="text-align: center;">{{ $v->rank }}</td>
                        <td class="border" style="text-align: center;">
                            <input {{$ro?'disabled':''}} type="text" value="{{ isset($sub_medal_items[$v->id]) ? $sub_medal_items[$v->id][0] : '' }}" class="form-control point_from" name="point_from[{{ $v->id }}]">
                        </td>
                        <td class="border" style="text-align: center;">
                            <input {{$ro?'disabled':''}} type="text" value="{{ isset($sub_medal_items[$v->id]) ? $sub_medal_items[$v->id][1] : '' }}" class="form-control point_to" name="point_to[{{ $v->id }}]">
                        </td>
                    </tr>
                @endforeach
            @endif
			</tbody>
		</table>
	</div>
</div>
