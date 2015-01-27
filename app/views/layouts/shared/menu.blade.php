@if (isset($menu))
  @foreach($menu as $menuItem)
      @if ($oldgroup === '' || $oldgroup !== $menuItem->group_name)
          {{ $oldgroup !== '' ? '</ul></li>' : '' }}
          <li id="{{Str::slug($menuItem->group_name)}}-dropdown" class="dropdown">{{  HTML::decode(link_to('#', $menuItem->group_name.'<b class="caret"></b>',array( 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown' ))) }}
            <ul id="{{Str::slug($menuItem->group_name)}}-dropdown-menu" name="{{Str::slug($menuItem->group_name)}}-dropdown-menu" class="dropdown-menu">
          <?php $oldgroup = $menuItem->group_name ?>
      @endif
          <li> {{  link_url($menuItem->name,$menuItem->display_name, $menuItem->action_name) }}</li>
  @endforeach
      </ul></li>
@endif
