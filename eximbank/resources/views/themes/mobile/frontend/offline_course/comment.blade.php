<div>
    <style>
        textarea {
            resize: none;
        }
        #count_message {
            background-color: #dee2e6;
            margin-top: -25px;
            margin-right: 5px;
            border-radius: 5px;
        }
    </style>
    <div class="student_reviews">
        <div class="row m-0">
            <div class="col-lg-12 p-0">
                <div class="review_right">
                    <div class="review_right_heading">
                        <h6>@lang('app.comment') ({{ $comments->count() }})</h6>
                    </div>
                </div>

                <div class="review_all120">
                    <form action="{{ route('themes.mobile.frontend.offline.comment', ['course_id' => $item->id, 'my_course' => $my_course]) }}" method="post" class="form-comment form-ajax">
                        @csrf
                        <div class="form-group">
                            <textarea name="content" class="form-control" maxlength="200" rows="5" id="content"></textarea>
                            <span class="float-right px-1" id="count_message"></span>
                        </div>
                        <button type="submit" class="btn w-100 p-2"><i class="fa fa-save"></i> @lang('app.send')</button>
                    </form>
                    <br>
                    <div>
                        @foreach($comments as $comment)
                            <div class="card shadow border-0 mt-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-1 p-0">
                                            <img src="{{ \App\Models\Profile::avatar($comment->user_id) }}" alt="" class="avatar avatar-30 no-shadow border-0">
                                        </div>
                                        <div class="col-11 pl-1 align-self-center">
                                            <p class="font-weight-normal mb-0">{{ \App\Models\Profile::fullname($comment->user_id) }}</p>
                                            <p class="text-mute text-secondary mb-0">
                                                {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                            </p>
                                            <p class="text-mute align-self-center">
                                                {{ ucfirst($comment->content) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var text_max = 200;
    $('#count_message').html('0 / ' + text_max );

    $('#content').keyup(function() {
        var text_length = $('#content').val().length;
        var text_remaining = text_max - text_length;
        $('#count_message').html(text_length + ' / ' + text_max);
    });
</script>
