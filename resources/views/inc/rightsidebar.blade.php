<!-- Right Sidebar -->
<aside id="rightsidebar" class="right-sidebar">
    <ul class="nav nav-tabs tab-nav-right" role="tablist">
        <li role="presentation" class="active"><a href="#skins" data-toggle="tab">@lang('lang.SKINS')</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
            <ul class="demo-choose-skin">
                <li data-theme="manhalgreen" class="active">
                    <div class="manhalgreen"></div>
                    <span>@lang('lang.Green')</span>
                </li>
                <li data-theme="green">
                    <div class="green"></div>
                    <span>@lang('lang.DeepGreen')</span>
                </li>
                <li data-theme="indigo">
                    <div class="indigo"></div>
                    <span>@lang('lang.Indigo')</span>
                </li>
                <li data-theme="purple">
                    <div class="purple"></div>
                    <span>@lang('lang.Purple')</span>
                </li>
                <li data-theme="orange">
                    <div class="orange"></div>
                    <span>@lang('lang.Orange')</span>
                </li>
                <li data-theme="deep-orange">
                    <div class="deep-orange"></div>
                    <span>@lang('lang.DeepOrange')</span>
                </li>
                <li data-theme="red" >
                    <div class="red"></div>
                    <span>@lang('lang.Red')</span>
                </li>
            </ul>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="settings">
            <div class="demo-settings">
                <p>@lang('lang.GENERALSETTINGS')</p>
                <ul class="setting-list">
                    <li>
                        <span>@lang('lang.ReportPanelUsage')</span>
                        <div class="switch">
                            <label><input type="checkbox" checked><span class="lever"></span></label>
                        </div>
                    </li>
                    <li>
                        <span>@lang('lang.EmailRedirect')</span>
                        <div class="switch">
                            <label><input type="checkbox"><span class="lever"></span></label>
                        </div>
                    </li>
                </ul>
                <p>@lang('lang.SYSTEMSETTINGS')</p>
                <ul class="setting-list">
                    <li>
                        <span>@lang('lang.NOTIFICATIONS')</span>
                        <div class="switch">
                            <label><input type="checkbox" checked><span class="lever"></span></label>
                        </div>
                    </li>
                    <li>
                        <span>@lang('lang.AutoUpdates')</span>
                        <div class="switch">
                            <label><input type="checkbox" checked><span class="lever"></span></label>
                        </div>
                    </li>
                </ul>
                <p>@lang('lang.ACCOUNTSETTINGS')</p>
                <ul class="setting-list">
                    <li>
                        <span>@lang('lang.Offline')</span>
                        <div class="switch">
                            <label><input type="checkbox"><span class="lever"></span></label>
                        </div>
                    </li>
                    <li>
                        <span>@lang('lang.LocationPermission')</span>
                        <div class="switch">
                            <label><input type="checkbox" checked><span class="lever"></span></label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>
<!-- #END# Right Sidebar -->