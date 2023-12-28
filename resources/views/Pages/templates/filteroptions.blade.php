<select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"> 
    <option value="{{route('showAllusers')}}">All Users</option>
    <option value="{{route('showActiveusers')}}">Currently Online</option>
    <option value="{{route('showInactiveusers')}}">Inactive Users</option>
</select>