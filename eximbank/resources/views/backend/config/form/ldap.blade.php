<div class="card">
    <div class="card-header">
        <h5 class="card-title">{{ trans('lasetting.general_ldap') }}</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('backend.config.save') }}" method="post" class="form-ajax">
            <div class="form-group">
                <label>{{ trans('lasetting.ldap_host') }}</label>
                <input type="text" class="form-control" name="ldap_host" value="{{ get_config('ldap_host') }}">
                <em class="description">{{ trans('lasetting.ldap_host_note') }}</em>
            </div>

            @php
            $ldap_version = get_config('ldap_version');
            @endphp
            <div class="form-group">
                <label>{{ trans('lasetting.version') }}</label>
                <select name="ldap_version" class="form-control">
                    <option value="3" @if($ldap_version == 3) selected @endif>3</option>
                    <option value="2" @if($ldap_version == 2) selected @endif>2</option>
                </select>
                <em class="description">{{ trans('lasetting.version_your_LDAP') }}</em>
            </div>

            @php
                $ldap_start_tls = get_config('ldap_start_tls');
            @endphp
            <div class="form-group">
                <label>{{ trans('lasetting.use_tls') }}</label>
                <select name="ldap_start_tls" class="form-control">
                    <option value="0" @if($ldap_start_tls == 0) selected @endif>{{ trans('lasetting.no') }}</option>
                    <option value="1" @if($ldap_start_tls == 1) selected @endif>{{ trans('lasetting.yes') }}</option>
                </select>
                <em class="description">{{ trans('lasetting.port_tls') }}</em>
            </div>

            <div class="form-group">
                <label>{{ trans('lasetting.distinguished_name') }}</label>
                <input type="text" class="form-control" name="ldap_dn" value="{{ get_config('ldap_dn') }}">
                <em>{{ trans('lasetting.binding_find') }}</em>
            </div>

            <div class="form-group">
                <label>{{ trans('lasetting.bind_password') }}</label>
                <input type="password" class="form-control" name="ldap_usr_dom" value="{{ get_config('ldap_usr_dom') }}">
                <em class="description">{{ trans('lasetting.password_binding') }}</em>
            </div>

            <div class="form-group">
                <label>{{ trans('lasetting.contexts') }}</label>
                <input type="text" class="form-control" name="ldap_contexts" value="{{ get_config('ldap_contexts') }}">
                <em class="description">{{ trans('lasetting.list_context') }}</em>
            </div>
            @can('config-save')
                <button type="submit" class="btn"><i class="fa fa-save"></i> @lang('labutton.save')</button>
            @endcan
        </form>
    </div>
</div>
