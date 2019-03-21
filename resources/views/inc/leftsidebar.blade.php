<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
	<!-- User Info -->
	@if(!Auth::guest())
		<div class="user-info">
			<div class="image">
				<img src="{{asset(\Auth::user()->avatar)}}" width="48" height="48" alt="User" />
			</div>
			<div class="info-container">
				<div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{Auth::user()->fullname }}</div>
				<div class="email">{{Auth::user()->email }}</div>
				<div class="btn-group user-helper-dropdown">
					<i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
					<ul class="dropdown-menu pull-right">
						<li><a href="{{url('/')."/".Lang::getLocale()}}/userprofile"><i class="material-icons">person</i>@lang('lang.User_Profile')</a></li>
						<li role="seperator" class="divider"></li>
						<li><a href="{{URL::to('/').'/'.Lang::getLocale().'/logout'}}" onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();"><i class="material-icons">input</i>Sign Out</a></li>
						<form id="logout-form" action="{{URL::to('/').'/'.Lang::getLocale().'/logout'}}" method="POST" style="display: none;">
							@csrf
						</form>

					</ul>
				</div>
			</div>
		</div>
@endif
<!-- #User Info -->
	<!-- Menu -->
	<div class="menu">
		<ol class="breadcrumb">
            <?php echo App\Helper\Breadcrumb::getBreadcrum(View::getSection('title'))?>
			{{--<li><a href="">page 2</a></li>
            <li class="active">page 3</li>--}}
		</ol>
		<ul class="list">
			<li class="{{Request::segment(2) === 'home' ||  Request::segment(2) == null ? 'active' : null }}" >
				<a href="{{url('/')."/".Lang::getLocale()}}/home">
					<i class="flaticon-home-page fi"></i>
					<span>@lang('lang.Home')</span>
				</a>
			</li>
			@if(\App\Users::isManhalAdmin() or \App\Users::isSchoolManger())
				<li class="{{ Request::segment(2) === 'admins' ? 'active' : null }}">
					<a href="{{url('/')."/".Lang::getLocale()}}/admins">
						{{--<i class="flaticon-management fi"></i>--}}
						<i class="flaticon1-support"></i>
						<span>@lang('lang.Admins')</span>
					</a>
				</li>
			@endif

				<li class="{{ Request::segment(2) === 'teachers' ? 'active' : null }}">
					<a href="{{url('/')."/".Lang::getLocale()}}/teachers">
						<i class="flaticon1-teacher"></i>
						<span>@lang('lang.Teachers')</span>
					</a>
				</li>

			@if(Auth::user()->permession < 5 or \App\Users::isParent() )
				<li class="{{ Request::segment(2) === 'students' ? 'active' : null }}">
					<a href="{{url('/')."/".Lang::getLocale()}}/students">
						<i class="flaticon1-university"></i>
						<span>@lang('lang.Students')</span>
					</a>
				</li>
			@endif
			@if(Auth::user()->permession < 5 )
				<li class="{{ Request::segment(2) === 'parents' ? 'active' : null }}">
					<a href="{{url('/')."/".Lang::getLocale()}}/parents">
						<i class="flaticon1-group"></i>
						<span>@lang('lang.Parents')</span>
					</a>
				</li>
			@endif
			@if(Auth::user()->permession < 5)
				<li class="{{ Request::segment(2) === 'levels' ? 'active' : null }}">
					<a href="{{url('/')."/".Lang::getLocale()}}/levels">
						<i class="flaticon1-graph"></i>
						<span>@lang('lang.Levels')</span>
					</a>
				</li>
			@endif
			<li class="{{ Request::segment(2) === 'curriculums' ? 'active' : null }}">
				<a href="{{url('/')."/".Lang::getLocale()}}/curriculums">
					<i class="flaticon1-book"></i>
					<span>@lang('lang.Curriculums')</span>
				</a>
			</li>
			@if(Auth::user()->permession < 5)
				<li class="{{ Request::segment(2) === 'classes' ? 'active' : null }}">
					<a href="{{url('/')."/".Lang::getLocale()}}/classes">
						<i class="flaticon1-theater"></i>
						<span>@lang('lang.Classes')</span>
					</a>
				</li>
			@endif
			<li class="{{ Request::segment(2) === 'groups' ? 'active' : null }}">
					<a href="{{url('/')."/".Lang::getLocale()}}/groups">
						<i class="flaticon-multiple-users-silhouette fi"></i>
						<span>@lang('lang.Groups')</span>
					</a>
				</li>

			<li class="{{ Request::segment(2) === 'category' ? 'active' : null }}">
					<a href="{{url('/')."/".Lang::getLocale()}}/category">
						<i class="flaticon-category fi"></i>
						<span>@lang('lang.Categories')</span>
					</a>
			</li>

			@if(Auth::user()->permession < 5)
				<li>
					<a href="javascript:void(0);" class="menu-toggle" id="li-standerd">
						<i class="flaticon1-done"></i>
						<span>@lang('lang.Standards')</span>
					</a>
					<ul class="ml-menu" id="ul-standerd">
						<li class="{{ Request::segment(2) === 'domains' ? 'active' : null }}">
							<a href="{{url('/')."/".Lang::getLocale()}}/domains">
								<i class="flaticon1-setup"></i>
								<span>@lang('lang.Domains')</span>
							</a>
						</li>
						<li class="{{ Request::segment(2) === 'pivots' ? 'active' : null }}">
							<a href="{{url('/')."/".Lang::getLocale()}}/pivots">
								<i class="flaticon1-menu"></i>
								<span>@lang('lang.Pivots')</span>
							</a>
						</li>
						<li class="{{ Request::segment(2) === 'standards' ? 'active' : null }}">
							<a href="{{url('/')."/".Lang::getLocale()}}/standards">
								<i class="flaticon1-done"></i>
								<span>@lang('lang.Standards')</span>
							</a>
						</li>
						<li class="{{ Request::segment(2) === 'competencies' ? 'active' : null }}">
							<a href="{{url('/')."/".Lang::getLocale()}}/competencies">
								<i class="flaticon1-list"></i>
								<span>@lang('lang.Competencies')</span>
							</a>
						</li>
					</ul>
				</li>
			@endif
			<li class="{{ Request::segment(2) === 'lessons' ? 'active' : null }}">
				<a href="{{url('/')."/".Lang::getLocale()}}/lessons">
					<i class="flaticon1-blackboard"></i>
					<span>@lang('lang.Lessons')</span>
				</a>
			</li>
			<li class="{{ Request::segment(2) === 'homework' ? 'active' : null }}">
				<a href="{{url('/')."/".Lang::getLocale()}}/homework">
					<i class="flaticon1-note"></i>
					<span>@lang('lang.Homework')</span>
				</a>
			</li>
			<li class="{{ Request::segment(2) === 'quiz' ? 'active' : null }}">
				<a href="{{url('/')."/".Lang::getLocale()}}/quiz">
					<i class="flaticon-test fi"></i>
					<span>@lang('lang.Quiz')</span>
				</a>
			</li>
			<li class="{{ Request::segment(2) === 'progress' ? 'active' : null }}">
				<a href="{{url('/')."/".Lang::getLocale()}}/progress">
					<i class="flaticon1-process"></i>
					<span>@lang('lang.Progress')</span>
				</a>
			</li>
			<li class="{{ Request::segment(2) === 'badges' ? 'active' : null }}">
				<a href="{{url('/')."/".Lang::getLocale()}}/badges">
					<i class="flaticon-prize-badge-with-star-and-ribbon fi"></i>
					<span>@lang('lang.Badges')</span>
				</a>
			</li>
			<li class="{{ Request::segment(2) === 'calender' ? 'active' : null }}">
				<a href="{{url('/')."/".Lang::getLocale()}}/calender" >
					<i class="flaticon-calendar-with-a-clock-time-tools fi"></i>
					<span>@lang('lang.Calendar')</span>
				</a>
			</li>
			<li class="{{ Request::segment(2) === 'schedule' ? 'active' : null }}">
				<a href="{{url('/')."/".Lang::getLocale()}}/schedule">
					<i class="flaticon-schedule-of-clases fi"></i>
					<span>@lang('lang.School_schedule')</span>
				</a>
			</li>
			{{--<li class="{{ Request::segment(2) === 'reports' ? 'active' : null }}">--}}
			{{--<a href="{{url('/')."/".Lang::getLocale()}}/reports">--}}
			{{--<i class="flaticon-computer fi"></i>--}}
			{{--<span>@lang('lang.Reports')</span>--}}
			{{--</a>--}}
			{{--</li>--}}
		</ul>
	</div>
	<!-- #Menu -->
	<!-- Footer -->
	<div class="legal">
		<div class="version">
		</div>
		<div class="copyright">
			<a href="javascript:void(0);">@lang('lang.CopyRights') © 2019</a>.
		</div>

	</div>
	<!-- #Footer -->
</aside>
<!-- #END# Left Sidebar -->