<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <form action="{{ route('themes.mobile.frontend.online.note', ['course_id' => $item->id]) }}" method="post" class="form-comment form-ajax" id="form-comment">
                @csrf
                <input type="hidden" name="id" value="{{ $notes->id }}">
                <div class="modal-header bg-template">
                    <span class="" data-dismiss="modal" aria-label="Close">
                        <i class="material-icons md-24 vm font-weight-bold">navigate_before</i>
                    </span>
                    <h6>{{ data_locale('Ghi chép', 'Note') }}</h6>
                    <span class="" id="submit_note"><i class="fa fa-check" style="font-size: 1.5em"></i></span>
                </div>
                <div class="modal-body p-0">
                    <textarea name="note_content" class="form-control" id="content_note" style="border: none; height: 80vh;" placeholder="Nhập nội dung cần ghi chép">{{ ucfirst($notes->note) }}</textarea>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#submit_note').on('click', function(){
        $('#form-comment').submit();
    });
</script>
