{{ html()->form('POST', route('landing_page_settings_updates'))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->id('frontend_setting')->open() }}
{{ html()->hidden('id', $landing_page->id)->class('form-control')->placeholder('id') }}
{{ html()->hidden('type', $tabpage)->class('form-control')->placeholder('id') }}

<div class="row">
    <div class="form-group">
        <div class="form-control d-flex align-items-center justify-content-between">
            <label for="enable_section_4" class="mb-0">Enable Section4</label>
            <div class="form-check form-switch m-0">
                <input type="checkbox" class="form-check-input section_4" name="status" id="section_4"
                    data-type="section_4" {{ !empty($landing_page) && $landing_page->status == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="section_4"></label>
            </div>
        </div>
    </div>
</div>
<div class="row" id='enable_section_4'>
    <div class="form-group col-md-6">
        {{ html()->label(trans('messages.title') . ' <span class="text-danger">*</span>', 'title')->class('form-control-label') }}
        {{ html()->text('title', old('title'))->id('title')->class('form-control')->placeholder(trans('messages.title')) }}
        <small class="help-block with-errors text-danger"></small>
    </div>

    <div class="form-group col-md-6">
        <label for="avatar" class="col-sm-6 form-control-label">Main Image</label>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    <img src="{{ getSingleMedia($landing_page, 'main_image') }}" width="100" id="main_image_preview"
                        alt="main_image" class="image main_image main_image_preview">
                    @if ($landing_page && getSingleMedia($landing_page, 'main_image', null))
                        <a class="text-danger remove-file" href="" data--submit="confirm_form"
                            data--confirmation='true' data--ajax="true"
                            title='{{ __('messages.remove_file_title', ['name' => __('messages.image')]) }}'
                            data-title='{{ __('messages.remove_file_title', ['name' => __('messages.image')]) }}'
                            data-message='{{ __('messages.remove_file_msg') }}'>
                            <i class="ri-close-circle-line"></i>
                        </a>
                    @endif
                </div>
                <div class="col-sm-8 mt-sm-0 mt-2">
                    <div class="custom-file col-md-12">
                        {{ html()->file('main_image')->class('custom-file-input custom-file-input-sm detail')->id('main_image')->attribute('lang', 'en')->attribute('accept', 'image/*')->attribute('onchange', 'preview()') }}
                        @if ($landing_page && getSingleMedia($landing_page, 'main_image'))
                            {{-- <label class="custom-file-label upload-label">{{ $landing_page->getFirstMedia('main_image')->file_name }}</label> --}}
                        @else
                            <label
                                class="custom-file-label upload-label">{{ __('messages.choose_file', ['file' => __('messages.attachments')]) }}</label>
                        @endif
                    </div>
                    {{-- <img id="main_image" src="" width="150px" /> --}}
                </div>
            </div>

        </div>
    </div>
    <div class="form-group col-md-6">
        <label for="avatar" class="col-sm-6 form-control-label">Google Image</label>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    <img src="{{ getSingleMedia($landing_page, 'google_play') }}" width="100"
                        id="google_play_preview" alt="google_play" class="image google_play google_play_preview">
                    @if ($landing_page && getSingleMedia($landing_page, 'google_play'))
                        <a class="text-danger remove-file" href="" data--submit="confirm_form"
                            data--confirmation='true' data--ajax="true"
                            title='{{ __('messages.remove_file_title', ['name' => __('messages.image')]) }}'
                            data-title='{{ __('messages.remove_file_title', ['name' => __('messages.image')]) }}'
                            data-message='{{ __('messages.remove_file_msg') }}'>
                            <i class="ri-close-circle-line"></i>
                        </a>
                    @endif
                </div>
                <div class="col-sm-8 mt-sm-0 mt-2">
                    <div class="custom-file col-md-12">
                        {{ html()->file('google_play')->class('custom-file-input custom-file-input-sm detail')->id('google_play')->attribute('lang', 'en')->attribute('accept', 'image/*')->attribute('onchange', 'preview()') }}

                        @if ($landing_page && getSingleMedia($landing_page, 'google_play'))
                            {{-- <label class="custom-file-label upload-label">{{ $landing_page->getFirstMedia('google_play')->file_name }}</label> --}}
                        @else
                            <label
                                class="custom-file-label upload-label">{{ __('messages.choose_file', ['file' => __('messages.attachments')]) }}</label>
                        @endif
                    </div>
                    {{-- <img id="google_play" src="" width="150px" /> --}}
                </div>
            </div>

        </div>
    </div>
    <div class="form-group col-md-6">
        <label for="avatar" class="col-sm-6 form-control-label">App Store</label>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    <img src="{{ getSingleMedia($landing_page, 'app_store') }}" width="100" id="app_store_preview"
                        alt="app_store" class="image app_store app_store_preview">
                    @if ($landing_page && getSingleMedia($landing_page, 'app_store'))
                        <a class="text-danger remove-file" href="" data--submit="confirm_form"
                            data--confirmation='true' data--ajax="true"
                            title='{{ __('messages.remove_file_title', ['name' => __('messages.image')]) }}'
                            data-title='{{ __('messages.remove_file_title', ['name' => __('messages.image')]) }}'
                            data-message='{{ __('messages.remove_file_msg') }}'>
                            <i class="ri-close-circle-line"></i>
                        </a>
                    @endif
                </div>
                <div class="col-sm-8 mt-sm-0 mt-2">
                    <div class="custom-file col-md-12">
                        {{ html()->file('app_store')->class('custom-file-input custom-file-input-sm detail')->id('app_store')->attribute('lang', 'en')->attribute('accept', 'image/*')->attribute('onchange', 'preview()') }}
                        @if ($landing_page && getSingleMedia($landing_page, 'app_store'))
                            {{-- <label class="custom-file-label upload-label">{{ $landing_page->getFirstMedia('app_store')->file_name }}</label> --}}
                        @else
                            <label
                                class="custom-file-label upload-label">{{ __('messages.choose_file', ['file' => __('messages.attachments')]) }}</label>
                        @endif
                    </div>
                    {{-- <img id="app_store" src="" width="150px" /> --}}
                </div>
            </div>

        </div>
    </div>
    <div class="form-group col-md-12">
        {{ html()->label(__('messages.description'), 'description')->class('form-control-label') }}
        {{ html()->textarea('description', null)->class('form-control textarea')->rows(2)->placeholder(__('messages.description')) }}
    </div>
</div>



{{ html()->submit(__('messages.save'))->class('btn btn-md btn-primary float-md-end submit_section1') }}
{{ html()->form()->close() }}


<script>
    var enable_section_4 = $("input[name='status']").prop('checked');
    checkSection4(enable_section_4);

    $('#section_4').change(function() {
        value = $(this).prop('checked') == true ? true : false;
        checkSection4(value);

    });

    function checkSection4(value) {
        if (value == true) {
            $('#enable_section_4').removeClass('d-none');
            $('#title').prop('required', true);
        } else {
            $('#enable_section_4').addClass('d-none');
            $('#title').prop('required', false);
        }
    }




    var get_value = $('input[name="status"]:checked').data("type");
    getConfig(get_value)
    $('.section_4').change(function() {
        value = $(this).prop('checked') == true ? true : false;
        type = $(this).data("type");
        getConfig(type)

    });

    function getConfig(type) {
        var _token = $('meta[name="csrf-token"]').attr('content');
        var page = "{{ $tabpage }}";
        var getDataRoute = "{{ route('getLandingLayoutPageConfig') }}";
        $.ajax({
            url: getDataRoute,
            type: "POST",
            data: {
                type: type,
                page: page,
                _token: _token
            },
            success: function(response) {
                var obj = '';
                var section_4 = title = description = '';

                if (response) {
                    if (response.data.key == 'section_4') {
                        obj = JSON.parse(response.data.value);
                    }
                    if (obj !== null) {
                        var title = obj.title;
                        var description = obj.description;
                    }
                    $('#title').val(title);
                    $('#description').val(description);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    function isImage(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'ico':
                return true;
        }
        return false;
    }

    function readURL(input, className) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var res = isImage(input.files[0].name);
            if (res == false) {
                var msg = 'Image should be png/PNG, jpg/JPG & jpeg/JPG.';
                Snackbar.show({
                    text: msg,
                    pos: 'bottom-right',
                    backgroundColor: '#d32f2f',
                    actionTextColor: '#fff'
                });
                $(input).val("");
                return false;
            }
            reader.onload = function(e) {
                $(document).find('img.' + className).attr('src', e.target.result);
                $(document).find("label." + className).text((input.files[0].name));
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $(document).ready(function() {
        $('.select2js').select2();
        $(document).on('change', '#main_image', function() {
            readURL(this, 'main_image');
        });
        $(document).on('change', '#google_play', function() {
            readURL(this, 'google_play');
        });
        $(document).on('change', '#app_store', function() {
            readURL(this, 'app_store');
        });
    })

    function preview() {
        var input = event.target;
        var previewImage;
        if (input.name === 'main_image') {
            previewImage = main_image;
        } else if (input.name === 'google_play') {
            previewImage = google_play;
        } else if (input.name === 'app_store') {
            previewImage = app_store;
        }
        previewImage.src = URL.createObjectURL(input.files[0]);
        var fileName = input.files[0].name;
        var label = $(input).closest('.custom-file').find('.custom-file-label');
        label.text(fileName);
    }
</script>
