<div class="container">
  <div class="navbar-header">
    {{ link_to('admin/', 'Admin',array('class'=>'navbar-brand')) }}
  </div>
  <div class="navbar-collapse collapse">
    <ul class="nav navbar-nav">
        @include('layouts.shared.menu')
    </ul>
    <ul class="nav navbar-nav navbar-right">
      @include('layouts.shared.menu-right')
    </ul>
  </div><!--/.nav-collapse -->
</div>
