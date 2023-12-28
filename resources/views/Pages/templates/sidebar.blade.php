    <a href="{{route('toindex')}}">Dashboard</a>

    <label for="">User</label>
    <select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"> 
    <option value="/Pages/Admin-User/displayusers/allusers" ><a >Registered Users</option>
    <option value="{{route('toShowRoles')}}">User Roles</option>
    <option value="{{route('toShowHcodes')}}">Honorific Codes</option>
    <option value="">User's Account Ledger</option>
    </select>
    
    <label for="">Address</label>
    <select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"> 
        <option value="{{route('toShowAddress')}}">Registered Addressses</option>
        <option value="{{route('toShowAddressTypes')}}">Address Types</option>
        <option value="{{route('toShowPostCode')}}">Post Codes</option>
        <option value="{{route('toShowPostOffice')}}">Post Offices</option>
        <option value="{{route('toShowStateCode')}}">State Codes</option>
    </select>

    <label for="">Subscription</label>
    <select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"> 
    <option value="">User Subscriptions</option>
    <option value="">Transactions</option>
    <option value="{{route('toShowPackage')}}">Subscription Packages</option>
    <option value="{{route('toShowPackageBase')}}">Package Base Information</option>
    </select>
    
    <label for="">Firms</label>
    <select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"> 
        <option value="">Registered Firms</option>
        <option value="{{route('toShowFirmTypes')}}">Firm Types</option>
    </select>

    <label for="">Business</label>
    <select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"> 
        <option value="{{route('toShowRegBusiness')}}">Registered Businesses</option>
        <option value="{{route('toDisplayBusinessType')}}">Business Types</option>
        <option value="{{route('toShowBusinessUser')}}">Registered Business Users</option>
    </select>

    <label for="">Orders</label>
    <select name="" id="">
        <option value="">Order Information</option>
        <option value="">Order Assignments</option>
    </select>
    
    <label for="">Miscellanous</label>
    <select name="" id="">
        <option value=""> Bank Reports</option>
        <option value="">Reference Tables</option>
    </select>
    <label for="">Settings</label>
    <select name="" id="">
        <option value=""> <a href="{{route('toprofile')}}">Admin Profile</option>
        <option value=""><a href="{{route('toSettings')}}">Other Settings</option>
    </select>
    
    <a href="{{route('logout.getservice')}}">Logout</a>
    