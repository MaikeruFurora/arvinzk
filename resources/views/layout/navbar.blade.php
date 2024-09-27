<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">ZKteco</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
         
          @if (strtolower(auth()->user()->user_type->name[0])==='u')
            <li class="nav-item">
              <a class="nav-link" href="{{ route('app.user.home') }}">Record</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('app.user.config.user.device') }}">Create User To Device</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('app.user.config') }}">Config</a>
            </li>
          @else
            <li class="nav-item">
              <a class="nav-link" href="{{ route('app.admin.home') }}">Device Config</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('app.admin.group') }}">Group</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('app.admin.user') }}">Users</a>
            </li>
          @endif
          <li class="nav-item">
            <a class="nav-link text-danger" href="{{ route('app.logout') }}">Logout</a>
          </li>
          {{-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Dropdown link
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li> --}}
        </ul>
      </div>
    </div>
</nav>