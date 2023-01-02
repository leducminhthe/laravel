<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @php
                    $isRating = \Modules\Online\Entities\OnlineRating::getRating($course_id, auth()->id());
                @endphp
                <div class="_rate002">{{ $isRating ? trans('laother.you_rated') : "" }}</div>
                <div class="total_rating pt-0">
                    <div class="rating-box">
                        @for ($i = 1; $i < 6; $i++)
                            <span class="rating-star
                                @if(!$isRating) empty-star rating
                                @elseif($isRating && $isRating->num_star >= $i) full-star
                                @else empty-star
                                @endif" data-value="{{ $i }}">
                            </span>
                        @endfor
                    </div>
                </div>
                @for ($i = 5; $i > 0; $i--)
                    <div class="_rate004">
                        @php
                            $star = $f_rating_star($i);
                        @endphp
                        <div class="row w-100 m-0 wrapped_rating">
                            <div class="col-6 p-0">
                                <div class="progress progress1">
                                    <div class="progress-bar w-{{ $star }}" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-3 p-1">
                                <div class="rating-box">
                                    @for ($ii = 0; $ii < 5; $ii++)
                                        <span class="rating-star @if ($ii < $i)
                                            full-star
                                        @else
                                            empty-star
                                        @endif"></span>
                                    @endfor
                                </div>
                            </div>
                            <div class="col-2 p-0">
                                <div class="_rate002">{{ $star }}%</div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
    <script>
        window.Rating = {
            route: '{{ route('module.online.rating', $course_id) }}',
        };
        var rating = $('.rating');
        ratingStars(rating);
    </script>
</div>

