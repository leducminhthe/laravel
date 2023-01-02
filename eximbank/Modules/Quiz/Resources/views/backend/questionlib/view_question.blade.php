<style>
    .mark_text{
        cursor: pointer;
    }
</style>
<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xem câu hỏi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        @if ($question->type == 'drag_drop_document')
                            @php
                                $partern = '/\[\[(.*?)\]\]/is';
                                $subject = $question->name;
                                $replacement = '<span class="p-2 border w-auto drop_document"></span>';
                            @endphp
                            <h5> {!! preg_replace($partern, $replacement, $subject) !!} </h5>
                        @elseif ($question->type == 'select_word_correct')
                            @php
                                $answer_arr = [];
                                $correct_answer_arr = [];
                                foreach($answers as $ans_key => $answer){
                                    $answer_arr[$answer->select_word_correct][] = $answer->title;
                                    if($answer->correct_answer > 0){
                                        $correct_answer_arr[$answer->select_word_correct] = '[['.$answer->select_word_correct.']]';
                                    }
                                }
                                $select = [];
                                foreach($answer_arr as $answer_key => $answer_item){
                                    $select_item = '<select>';
                                    foreach($answer_item as $item){
                                        $select_item .= '<option>'.$item.'</option>';
                                    }
                                    $select_item .= '</select>';

                                    $select[] = $select_item;
                                }
                            @endphp
                            <h5> {!! str_replace($correct_answer_arr, $select, $question->name) !!} </h5>
                        @else
                            <h5> {!! $question->name !!} </h5>
                        @endif
                    </div>
                    @php
                        $answer_text = range('a', 'z');
                    @endphp
                    <div class="col-12 mt-2">
                        @if($question->type == 'essay')
                            <input type="file" class="">
                            <textarea class="form-control" rows="5" placeholder="Nhập đáp án"></textarea>
                        @endif
                        @if($question->type == 'fill_in')
                            @foreach($answers as $ans_key => $answer)
                                <p>
                                    {!! $answer_text[$ans_key] .'. '. $answer->title !!}
                                    <textarea class="form-control" placeholder="Nhập đáp án"></textarea>
                                </p>
                            @endforeach
                        @endif
                        @if($question->type == 'fill_in_correct')
                            @foreach($answers as $ans_key => $answer)
                                <p>
                                    {!! $answer_text[$ans_key] .'. '. $answer->title !!}
                                    <textarea class="form-control" placeholder="Nhập đáp án"></textarea>
                                </p>
                            @endforeach
                        @endif
                        @if($question->type == 'multiple-choise')
                            @if($question->answer_horizontal != 0)
                                <div class="row">
                            @endif
                            @foreach($answers as $ans_key => $answer)
                                @if($question->answer_horizontal != 0)
                                    <div class="col-{{ 12/$question->answer_horizontal }} p-1">
                                @endif
                                    <p>
                                        <input type="{{ $question->multiple == 1 ? 'checkbox' : 'radio' }}"
                                            @if ($question->multiple == 0) name="answer_choise" @endif
                                        >
                                        {!! $answer_text[$ans_key] . ( $answer->title ? '. '. $answer->title : '') !!} <br>
                                        @if($answer->image_answer)
                                            <img src="{{ image_file($answer->image_answer) }}" alt="" class="w-50 img-responsive">
                                        @endif
                                    </p>
                                @if($question->answer_horizontal != 0)
                                    </div>
                                @endif
                            @endforeach
                            @if($question->answer_horizontal != 0)
                                </div>
                            @endif
                        @endif
                        @if($question->type == 'matching')
                            @foreach($answers as $ans_key => $answer)
                                <p>
                                    {!! $answer_text[$ans_key] .'. '. $answer->title  !!}
                                    <select class="form-control">
                                        @foreach($answers as $ans_key => $answer)
                                            <option value="">{{ $answer->matching_answer }}</option>
                                        @endforeach
                                    </select>
                                </p>
                            @endforeach
                        @endif
                        @if ($question->type == 'drag_drop_marker')
                            <div class="" id="image-area w-100">
                                <img src="{{ image_file($question->image_drag_drop) }}" class="border mb-2" id="image_drag_drop">
                                <div class="mt-2" id="list_mark_text">
                                    @if (isset($answers))
                                        @foreach ($answers as $ans_key => $answer)
                                            <span class="m-1 p-2 border mark_text" id="mark_text_{{ $ans_key }}" draggable="true" >
                                                {{ $answer->title }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if ($question->type == 'drag_drop_image')
                            <div class="" id="image-area w-100">
                                <img src="{{ image_file($question->image_drag_drop) }}" class="border mb-2">
                                @php
                                    $marker_answer = [];
                                @endphp
                                <div class="mt-2" id="list_mark_text">
                                    @if (isset($answers))
                                        @foreach ($answers as $ans_key => $answer)
                                            @if ($answer->marker_answer)
                                                @php
                                                    $marker_answer[] = $answer->marker_answer;
                                                @endphp
                                            @endif
                                            @if ($answer->image_answer)
                                                <img src="{{ image_file($answer->image_answer) }}" class="mark_text answer_image border" style="max-width: 150px;" draggable="true">
                                            @else
                                                <span class="m-1 p-2 mark_text border" draggable="true">{{ $answer->title }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                @foreach ($marker_answer as $ans_key => $item)
                                    @php
                                        $left = explode(',', $item)[0] .'px';
                                        $top = explode(',', $item)[1] .'px';
                                    @endphp
                                    <span id="marker_answer{{ $ans_key }}" class="p-2 border w-auto drop_document"
                                        style = "position: absolute; top: {{ $top }}; left: {{ $left }};">
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        @if ($question->type == 'drag_drop_document')
                            <div class="mt-5" id="list_mark_text">
                                @if (isset($answers))
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($answers as $ans_key => $answer)
                                        @if ($answer->select_word_correct > $i)
                                            <p></p><p></p>
                                            @php
                                                $i += 1;
                                            @endphp
                                        @endif
                                        <span class="m-1 p-2 mark_text" id="mark_text_{{ $ans_key }}" draggable="true">
                                            {{ $answer->title }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
    <script>
        var image_drag_drop = document.getElementById("image_drag_drop"); //Hình ảnh nền kéo thả
        var image_area = document.getElementById("image-area"); //Khu vực kéo thả chứa hình nền
        var mark_text_arr = document.querySelectorAll('.mark_text'); //Đối tượng kéo thả
        var drop_document_arr = document.querySelectorAll('.drop_document'); //Khu vực thả cho văn bản
        var list_mark_text = document.getElementById("list_mark_text");
        var current_target = null;

        mark_text_arr.forEach(mark_text => {
            mark_text.addEventListener('dragstart', function(e){
                current_target = this;
            });
        });

        if(image_drag_drop){
            image_drag_drop.addEventListener('dragover', function(e){
                e.preventDefault();

                current_target.style.position = 'absolute';
                current_target.style.left = e.offsetX + 'px';
                current_target.style.top = e.offsetY + 'px';
            });

            image_drag_drop.addEventListener('drop', function(e){
                image_area.appendChild(current_target);
            });
        }

        drop_document_arr.forEach(drop_document => {
            drop_document.addEventListener('dragover', function(e){
                e.preventDefault();
            });

            drop_document.addEventListener('drop', function(e){
                if (!drop_document.querySelector('.mark_text')) {
                    current_target.classList.remove('border');
                    this.appendChild(current_target);
                }
            });
        });

        if(list_mark_text){
            list_mark_text.addEventListener('dragover', function(e){
                e.preventDefault();
            });

            list_mark_text.addEventListener('drop', function(e){
                current_target.classList.add('border');
                this.appendChild(current_target);
            });
        }

    </script>
</div>
