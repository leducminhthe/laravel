@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.scan_qrcode') )

@section('header')
    <script language="javascript" src="{{ asset('styles/module/qrcode/js/vue.min.js') }}"></script>
    <script language="javascript" src="{{ asset('styles/module/qrcode/js/vue-qrcode-reader.browser.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/module/qrcode/css/vue-qrcode-reader.css') }}">
    <style>
        .qrcode-stream {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div id="app">
            <qrcode-stream @init="onInit" @decode="onDecode" :paused="paused">
                <div v-if="decodedContent !== null" class="decoded-content"></div>
            </qrcode-stream>

            <div class="error">
                @{{ errorMessage }}
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">//<![CDATA[
        Vue.use(VueQrcodeReader);
        new Vue({
            el: '#app',

            data() {
                return {
                    paused: false,
                    decodedContent: null,
                    errorMessage: ''
                }
            },

            methods: {
                async onDecode (content) {
                    this.camera = false;
                    let URI = new URL(content)
                    let urlMobi = URI.pathname+URI.search;
                    // var param = $.param(JSON.parse(content));
                    var url = '{{url('/AppM/')}}'+urlMobi+'&user={{auth()->id()}}';
                    {{--                    var url = '{{url('AppM/qrcode/process') }}?user={{auth()->id()}}&' + param;--}}
                        window.location.href = url;
                    this.decodedContent = content;
                },

                onInit(promise) {
                    promise.then(() => {
                        console.log('Successfully initilized! Ready for scanning now!')
                    })
                        .catch(error => {
                            if (error.name === 'NotAllowedError') {
                                this.errorMessage = "{!!__('app.qrcode_NotAllowedError')!!}"
                            } else if (error.name === 'NotFoundError') {
                                this.errorMessage = "{!!__('app.qrcode_NotFoundError')!!}"
                            } else if (error.name === 'NotSupportedError') {
                                this.errorMessage = "{!!__('app.qrcode_NotSupportedError')!!}"
                            } else if (error.name === 'NotReadableError') {
                                this.errorMessage = "{!!__('app.qrcode_NotReadableError') !!}"
                            } else if (error.name === 'OverconstrainedError') {
                                this.errorMessage = "{!!__('app.qrcode_OverconstrainedError')!!}"
                            } else {
                                this.errorMessage = "{!!__('app.qrcode_error_unknow')!!}"
                            }
                        })
                }
            }
        })
        //]]>

        var height = $('#app').height();
        console.log(height);
    </script>
@endsection
