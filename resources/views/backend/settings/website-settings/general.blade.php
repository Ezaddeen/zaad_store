@extends('backend.master')

{{-- تعريب عنوان الصفحة --}}
@section('title', __('settings.general_settings'))

@section('content')

<div class="row">
    <div class="col-4 col-sm-2">
        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
            @can('website_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'website-info' ? 'active' : '' }}" id="vert-tabs-1"
                data-toggle="pill" href="#tabs-1" role="tab" aria-controls="tabs-1" aria-selected="true">
                <i class="fas fa-desktop"></i>
                &nbsp;{{ __('settings.website_info') }}
            </a>
            @endcan
            @can('contact_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'contacts' ? 'active' : '' }}" id="vert-tabs-2"
                data-toggle="pill" href="#tabs-2" role="tab" aria-controls="tabs-2" aria-selected="false">
                <i class="fas fa-address-book"></i>
                &nbsp;{{ __('settings.contacts') }}
            </a>
            @endcan
            @can('socials_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'social-links' ? 'active' : '' }}" id="vert-tabs-3"
                data-toggle="pill" href="#tabs-3" role="tab" aria-controls="tabs-3" aria-selected="false">
                <i class="fas fa-share-alt"></i>
                &nbsp;{{ __('settings.social_links') }}
            </a>
            @endcan
            @can('style_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'style-settings' ? 'active' : '' }}" id="vert-tabs-4"
                data-toggle="pill" href="#tabs-4" role="tab" aria-controls="tabs-4" aria-selected="false">
                <i class="fas fa-swatchbook"></i>
                &nbsp;{{ __('settings.style_settings') }}
            </a>
            @endcan
            @can('custom_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'custom-css' ? 'active' : '' }}" id="vert-tabs-5"
                data-toggle="pill" href="#tabs-5" role="tab" aria-controls="tabs-5" aria-selected="false">
                <i class="fas fa-code"></i>
                &nbsp;{{ __('settings.custom_css') }}
            </a>
            @endcan
            @can('notification_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'notification-settings' ? 'active' : '' }}" id="vert-tabs-6"
                data-toggle="pill" href="#tabs-6" role="tab" aria-controls="tabs-6" aria-selected="false">
                <i class="fas fa-envelope"></i>
                &nbsp;{{ __('settings.notification_settings') }}
            </a>
            @endcan
            @can('website_status_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'website-status' ? 'active' : '' }}" id="vert-tabs-7"
                data-toggle="pill" href="#tabs-7" role="tab" aria-controls="tabs-7" aria-selected="false">
                <i class="fas fa-power-off"></i>
                &nbsp;{{ __('settings.website_status') }}
            </a>
            @endcan
            @can('invoice_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'invoice-settings' ? 'active' : '' }}" id="vert-tabs-8"
                data-toggle="pill" href="#tabs-8" role="tab" aria-controls="tabs-8" aria-selected="false">
                <i class="fas fa-file-invoice"></i>
                &nbsp;{{ __('settings.invoice_settings') }}
            </a>
            @endcan
        </div>
    </div>
    <div class="col-8 col-sm-10">
        <div class="tab-content" id="vert-tabs-tabContent">
            {{-- تبويبة معلومات الموقع --}}
            @can('website_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'website-info' ? 'active show' : '' }}" id="tabs-1"
                role="tabpanel" aria-labelledby="vert-tabs-1">

                <form action="{{ route('backend.admin.settings.website.info.update') }}" method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-desktop"></i>
                            &nbsp;&nbsp;{{ __('settings.website_info') }}
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;{{ __('general.save_changes') }}
                        </button>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __('settings.website_title') }}</label>
                            <input class="form-control" name="site_name" type="text"
                                value="{{ readConfig('site_name') }}" placeholder="{{ __('settings.enter_site_title') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('settings.meta_description') }}</label>
                            <textarea class="form-control" rows="2" name="meta_description" cols="50"
                                placeholder="{{ __('settings.enter_meta_description') }}">{{ readConfig('meta_description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>{{ __('settings.meta_keywords') }}</label>
                            <textarea class="form-control" rows="2" name="meta_keywords" cols="50" placeholder="{{ __('settings.enter_keywords') }}">{{ readConfig('meta_keywords') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>{{ __('settings.website_url') }}</label>
                            <input class="form-control" name="site_url" type="url"
                                value="{{ readConfig('site_url') }}" placeholder="{{ __('settings.enter_site_url') }}">
                        </div>
                    </div>
                </form>

            </div>
            @endcan
            {{-- تبويبة جهات الاتصال --}}
            @can('contact_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'contacts' ? 'active show' : '' }}" id="tabs-2"
                role="tabpanel" aria-labelledby="vert-tabs-2">

                <form action="{{ route('backend.admin.settings.website.contacts.update') }}" method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-address-book"></i>
                            &nbsp;&nbsp;{{ __('settings.contacts') }}
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;{{ __('general.save_changes') }}
                        </button>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __('settings.address') }}</label>
                            <input placeholder="{{ __('settings.address') }}" class="form-control" name="contact_address" type="text"
                                value="{{ readConfig('contact_address') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('settings.phone') }}</label>
                            <input placeholder="{{ __('settings.phone') }}" class="form-control" name="contact_phone" type="tel"
                                value="{{ readConfig('contact_phone') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('settings.fax') }}</label>
                            <input placeholder="{{ __('settings.fax') }}" class="form-control" name="contact_fax" type="tel"
                                value="{{ readConfig('contact_fax') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('settings.mobile') }}</label>
                            <input placeholder="{{ __('settings.mobile') }}" class="form-control" name="contact_mobile" type="tel"
                                value="{{ readConfig('contact_mobile') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('settings.email') }}</label>
                            <input placeholder="{{ __('settings.email') }}" class="form-control" name="contact_email" type="email"
                                value="{{ readConfig('contact_email') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('settings.working_time') }}</label>
                            <input placeholder="{{ __('settings.working_time_placeholder') }}" class="form-control"
                                name="working_hour" type="text" value="{{ readConfig('working_hour') }}">
                        </div>
                    </div>
                </form>

            </div>
            @endcan
            {{-- تبويبة الروابط الاجتماعية --}}
            @can('socials_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'social-links' ? 'active show' : '' }}"
                id="tabs-3" role="tabpanel" aria-labelledby="vert-tabs-3">
                <form action="{{ route('backend.admin.settings.website.social.link.update') }}" method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-share-alt"></i>
                            &nbsp;&nbsp;{{ __('settings.social_links') }}
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;{{ __('general.save_changes') }}
                        </button>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>
                                <i class="fab fa-facebook"></i>
                                &nbsp; Facebook
                            </label>
                            <input placeholder="Facebook" class="form-control" name="facebook_link" type="url"
                                value="{{ readConfig('facebook_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-twitter"></i>
                                &nbsp; Twitter
                            </label>
                            <input placeholder="Twitter" class="form-control" name="twitter_link" type="url"
                                value="{{ readConfig('twitter_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-linkedin"></i>
                                &nbsp; Linkedin
                            </label>
                            <input placeholder="Linkedin" class="form-control" name="linkedin_link" type="url"
                                value="{{ readConfig('linkedin_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-youtube"></i>
                                &nbsp; Youtube
                            </label>
                            <input placeholder="Youtube" class="form-control" name="youtube_link" type="url"
                                value="{{ readConfig('youtube_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-instagram"></i>
                                &nbsp; Instagram
                            </label>
                            <input placeholder="Instagram" class="form-control" name="instagram_link" type="url"
                                value="{{ readConfig('instagram_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-pinterest"></i>
                                &nbsp; Pinterest
                            </label>
                            <input placeholder="Pinterest" class="form-control" name="pinterest_link" type="url"
                                value="{{ readConfig('pinterest_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-tumblr"></i>
                                &nbsp; Tumblr
                            </label>
                            <input placeholder="Tumblr" class="form-control" name="tumblr_link" type="url"
                                value="{{ readConfig('tumblr_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-snapchat"></i>
                                &nbsp; Snapchat
                            </label>
                            <input placeholder="Snapchat" class="form-control" name="snapchat_link" type="url"
                                value="{{ readConfig('snapchat_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-whatsapp"></i>
                                &nbsp; Whatsapp
                            </label>
                            <input placeholder="Whatsapp" class="form-control" name="whatsapp_link" type="url"
                                value="{{ readConfig('whatsapp_link') }}">
                        </div>
                    </div>
                </form>
            </div>
            @endcan
            {{-- تبويبة إعدادات التصميم --}}
            @can('style_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'style-settings' ? 'active show' : '' }}"
                id="tabs-4" role="tabpanel" aria-labelledby="vert-tabs-4">

                <form action="{{ route('backend.admin.settings.website.style.settings.update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-swatchbook"></i>
                            &nbsp;&nbsp;{{ __('settings.style_settings') }}
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;{{ __('general.save_changes') }}
                        </button>
                    </div>

                    <div class="col-12 my-2">
                        <label>{{ __('settings.site_logo') }}</label>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-12 box p-a-xs text-center">
                                    <img src="{{ assetImage(readconfig('site_logo')) }}"
                                        class="img-fluid thumbnail-preview site-logo-placeholder">
                                </div>
                            </div>
                        </div>
                        <input class="form-control" accept="image/*" name="site_logo" type="file"
                            onchange="previewThumbnail(this)">
                        <small>
                            <i class="far fa-question-circle"></i>
                            {{ __('settings.logo_dimensions') }} - Extensions: .png, .jpg, .jpeg, .gif, .svg
                        </small>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="style_fav">{{ __('settings.favicon') }}</label>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-12 box p-a-xs text-center">
                                        <a target="_blank" href="{{ assetImage(readconfig('favicon_icon')) }}">
                                            <img src="{{ assetImage(readconfig('favicon_icon')) }}"
                                                class="img-fluid thumbnail-preview site-logo-placeholder">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <input class="form-control" accept="image/*" name="favicon_icon" type="file"
                                onchange="previewThumbnail(this)">
                            <small>
                                <i class="far fa-question-circle"></i>
                                {{ __('settings.favicon_dimensions') }} - Extensions: .png, .jpg, .jpeg, .gif, .svg
                            </small>
                        </div>
                        <div class="col-sm-6">
                            <label for="style_apple">{{ __('settings.apple_icon') }}</label>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-12 box p-a-xs text-center">
                                        <a target="_blank" href="{{ assetImage(readconfig('favicon_icon_apple')) }}">
                                            <img src="{{ assetImage(readconfig('favicon_icon_apple')) }}"
                                                class="img-fluid thumbnail-preview site-logo-placeholder">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <input class="form-control" accept="image/*" name="favicon_icon_apple" type="file"
                                onchange="previewThumbnail(this)">
                            <small>
                                <i class="far fa-question-circle"></i>
                                {{ __('settings.apple_icon_dimensions') }} - Extensions: .png, .jpg, .jpeg, .gif, .svg
                            </small>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>{{ __('settings.newsletter_subscribe') }}</label>
                        <div class="radio bg-white rounded pt-2 pl-2 border">
                            <label class="ui-check ui-check-md">
                                <input {{ readConfig('newsletter_subscribe') == 1 ? 'checked' : '' }}
                                    name="newsletter_subscribe" type="radio" value="1">
                                <i class="dark-white"></i>
                                {{ __('general.active') }}
                            </label>
                            &nbsp; &nbsp;
                            <label class="ui-check ui-check-md">
                                <input {{ readConfig('newsletter_subscribe') == 0 ? 'checked' : '' }}
                                    name="newsletter_subscribe" type="radio" value="0">
                                <i class="dark-white"></i>
                                {{ __('general.not_active') }}
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            @endcan
            {{-- تبويبة كود CSS مخصص --}}
            @can('custom_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'custom-css' ? 'active show' : '' }}" id="tabs-5"
                role="tabpanel" aria-labelledby="vert-tabs-5">
                <form action="{{ route('backend.admin.settings.website.custom.css.update') }}" method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-code"></i>
                            &nbsp;&nbsp;{{ __('settings.custom_css') }}
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;{{ __('general.save_changes') }}
                        </button>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <textarea placeholder="" class="form-control" rows="17" name="custom_css" cols="50">{{ readConfig('custom_css') }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
            @endcan
            {{-- تبويبة إعدادات الإشعارات --}}
            @can('notification_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'notification-settings' ? 'active show' : '' }}"
                id="tabs-6" role="tabpanel" aria-labelledby="vert-tabs-6">
                <form action="{{ route('backend.admin.settings.website.notification.settings.update') }}"
                    method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-envelope"></i>
                            &nbsp;&nbsp;{{ __('settings.notification_settings') }}
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;{{ __('general.save_changes') }}
                        </button>
                    </div>
                    <div class="p-a-md col-md-12">
                        <div class="form-group">
                            <label>{{ __('settings.website_notification_email') }}</label>
                            <input placeholder="{{ __('general.enter_email') }}" class="form-control" name="notify_email_address"
                                type="email" value="{{ readConfig('notify_email_address') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('settings.email_on_new_messages') }} </label>
                            <div class="radio bg-white rounded pt-2 pl-2 border">
                                <label class="ui-check ui-check-md">
                                    <input {{ readConfig('notify_messages_status') == 1 ? 'checked' : '' }}
                                        name="notify_messages_status" type="radio" value="1">
                                    <i class="dark-white"></i>
                                    {{ __('general.yes') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    <input {{ readConfig('notify_messages_status') == 0 ? 'checked' : '' }}
                                        name="notify_messages_status" type="radio" value="0">
                                    <i class="dark-white"></i>
                                    {{ __('general.no') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ __('settings.email_on_new_comments') }} </label>
                            <div class="radio bg-white rounded pt-2 pl-2 border">
                                <label class="ui-check ui-check-md">
                                    <input {{ readConfig('notify_comments_status') == 1 ? 'checked' : '' }}
                                        name="notify_comments_status" type="radio" value="1">
                                    <i class="dark-white"></i>
                                    {{ __('general.yes') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    <input {{ readConfig('notify_comments_status') == 0 ? 'checked' : '' }}
                                        name="notify_comments_status" type="radio" value="0">
                                    <i class="dark-white"></i>
                                    {{ __('general.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @endcan
            {{-- تبويبة حالة الموقع --}}
            @can('website_status_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'website-status' ? 'active show' : '' }}"
                id="tabs-7" role="tabpanel" aria-labelledby="vert-tabs-7">
                <form action="{{ route('backend.admin.settings.website.status.update') }}" method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-power-off"></i>
                            &nbsp;&nbsp;{{ __('settings.website_status') }}
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;{{ __('general.save_changes') }}
                        </button>
                    </div>
                    <div class="p-a-md col-md-12">
                        <div class="form-group">
                            <label>{{ __('settings.website_current_status') }} </label>
                            <div class="radio bg-white rounded pt-2 pl-2 border">
                                <label class="ui-check ui-check-md">
                                    <input {{ readConfig('is_live') == 1 ? 'checked' : '' }} name="is_live"
                                        type="radio" value="1">
                                    <i class="dark-white"></i>
                                    {{ __('general.active') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    <input {{ readConfig('is_live') == 0 ? 'checked' : '' }} name="is_live"
                                        type="radio" value="0">
                                    <i class="dark-white"></i>
                                    {{ __('general.not_active') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group {{ readConfig('is_live') == 1 ? 'd-none' : '' }}" id="close_msg_div">
                            <label>{{ __('settings.close_message') }}</label>
                            <textarea placeholder="{{ __('settings.close_message') }}" class="form-control" rows="4" name="close_msg" cols="50">{{ readConfig('close_msg') ?: __('settings.default_close_message') }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
            @endcan
            {{-- تبويبة إعدادات الفواتير --}}
            @can('invoice_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'invoice-settings' ? 'active show' : '' }}"
                id="tabs-8" role="tabpanel" aria-labelledby="vert-tabs-8">
                <form action="{{ route('backend.admin.settings.website.invoice.update') }}" method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-file-invoice"></i>
                            &nbsp;&nbsp;{{ __('settings.invoice_settings') }}
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;{{ __('general.save_changes') }}
                        </button>
                    </div>
                    <div class="form-group">
                        <label>{{ __('settings.note_to_customer') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('settings.enter_message_for_invoice') }}" name="note_to_customer_invoice" value="{{ readConfig('note_to_customer_invoice') }}">
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_logo_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_logo_invoice') == 1 ? 'checked' : '' }} name="is_show_logo_invoice" id="is_show_logo_invoice" value="{{ readConfig('is_show_logo_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>
                        <label for="is_show_logo_invoice" class="mx-2">{{ __('settings.show_logo') }}</label>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_site_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_site_invoice') == 1 ? 'checked' : '' }} name="is_show_site_invoice" id="is_show_site_invoice" value="{{ readConfig('is_show_site_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>
                        <label for="is_show_site_invoice" class="mx-2">{{ __('settings.show_site_name') }}</label>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_phone_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_phone_invoice') == 1 ? 'checked' : '' }} name="is_show_phone_invoice" id="is_show_phone_invoice" value="{{ readConfig('is_show_phone_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>

                        <label for="is_show_phone_invoice" class="mx-2">{{ __('settings.show_phone') }}</label>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_email_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_email_invoice') == 1 ? 'checked' : '' }} name="is_show_email_invoice" id="is_show_email_invoice" value="{{ readConfig('is_show_email_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>
                        <label for="is_show_email_invoice" class="mx-2">{{ __('settings.show_email') }}</label>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_address_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_address_invoice') == 1 ? 'checked' : '' }} name="is_show_address_invoice" id="is_show_address_invoice" value="{{ readConfig('is_show_address_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>
                        <label for="is_show_address_invoice" class="mx-2">{{ __('settings.show_address') }}</label>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_customer_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_customer_invoice') == 1 ? 'checked' : '' }} name="is_show_customer_invoice" id="is_show_customer_invoice" value="{{ readConfig('is_show_customer_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>
                        <label for="is_show_customer_invoice" class="mx-2">{{ __('settings.show_customer') }}</label>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_note_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkb
                            <span class="slider round"></span>
                        </label>
                        <label for="is_show_note_invoice" class="mx-2">{{ __('settings.show_note') }}</label>
                    </div>
                </form>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection