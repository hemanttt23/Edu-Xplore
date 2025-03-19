<nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
          <a class="navbar-brand me-5" href="#">
            <img src="../logo.jpg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
            Edu-Xplore
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto">
              <li class="nav-item ms-5">
                <a class="nav-link <?php if($page_name == "Home Page") {echo 'active';} ?> " aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item ms-3">
                <a class="nav-link" href="#">Features</a>
              </li>
              <li class="nav-item ms-3">
                <a class="nav-link" href="#">Pricing</a>
              </li>
              <li class="nav-item dropdown ms-3">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Dropdown link
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </li>
            </ul>
            <form class="d-flex me-4" role="search">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
          </div>
        </div>
      </nav>