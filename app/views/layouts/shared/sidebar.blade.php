<div class="bs-docs-sidebar hidden-print" role="complementary">
    <div class="row">
        @if (isset($user))
            <div class="col-xs-12">
                <select id="id-field-users" name="users" class="chosen-select form-control" tabindex="-1">
                     @foreach($user->roles->all() as $role)
                         <option value="{{ $role->id }}" {{ $role->id == Session::get('current_role_id') ? "selected" : '' }} >{{{ $role->display_name }}}  </option>
                     @endforeach
                </select>
            </div>
        @endif
        <div class="col-xs-12">
            <ul class="nav bs-docs-sidenav">
                @foreach($menu as $menuItem)
                    @if ($oldgroup === '' || $oldgroup !== $menuItem->group_name)
                        {{ $oldgroup !== '' ? '</ul></li>' : '' }}
                        <li class="active"> {{  link_to('#'.$menuItem->action_name, $menuItem->group_name) }} <ul class="nav">
                        <?php $oldgroup = $menuItem->group_name ?>
                    @endif
                        <li> {{  link_url($menuItem->name,$menuItem->display_name, $menuItem->action_name) }}</li>
                @endforeach
                    </ul></li>
            </ul>
        </div>
    </div>
</div>

