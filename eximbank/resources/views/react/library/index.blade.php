@extends('react.layouts.app')
@section('page_title', trans('lalibrary.library'))

@section('content')
    <div id="languages"
        data-library="{{ trans('lalibrary.library') }}"
        data-book="{{ trans('lalibrary.book') }}"
        data-ebook="{{ trans('lalibrary.ebook') }}"
        data-document="{{ trans('lalibrary.document') }}"
        data-audiobook="{{ trans('lalibrary.audiobook') }}"
        data-category="{{ trans('lalibrary.category') }}"
        data-bookName="{{ trans('lalibrary.book_name') }}"
        data-nameAuthor="{{ trans('lalibrary.name_author') }}"
        data-status="{{ trans('lalibrary.status') }}"
        data-register="{{ trans('lalibrary.register') }}"
        data-approved="{{ trans('lalibrary.approved') }}"
        data-borrow="{{ trans('lalibrary.borrow') }}"
        data-delete_search="{{ trans('lalibrary.delete_search') }}"
        data-num_books_remaining="{{ trans('lalibrary.num_books_remaining') }}"
        data-it_over="{{ trans('lalibrary.it_over') }}"
        data-amount="{{ trans('lalibrary.amount') }}"
        data-registered="{{ trans('lalibrary.registered') }}"
        data-book_same_category="{{ trans('lalibrary.book_same_category') }}"
        data-ebook_same_category="{{ trans('lalibrary.ebook_same_category') }}"
        data-document_same_category="{{ trans('lalibrary.document_same_category') }}"
        data-audioobok_same_category="{{ trans('lalibrary.audioobok_same_category') }}"
        data-video_same_category="{{ trans('lalibrary.video_same_category') }}"
        data-description="{{ trans('lalibrary.description') }}"
        data-view="{{ trans('laother.see') }}"
        data-download="{{ trans('lalibrary.download') }}"
        data-note_time_borrow_book="{{ trans('lalibrary.note_time_borrow_book') }}"
        data-note_give_back_book="{{ trans('lalibrary.note_give_back_book') }}"
        data-note_contact_give_back_book="{{ trans('lalibrary.note_contact_give_back_book') }}"
        data-home_page="{{ trans('lamenu.home_page') }}"
    >
    </div>
    <div id="react" class="sa4d25">
                    
    </div>
@endsection
