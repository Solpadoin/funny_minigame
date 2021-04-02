<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css">
    <link rel="stylesheet" href="css/main.css">

    <title>Web Game - Cube</title>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="js/cube.js"></script>
    <script src="js/jquery.js"></script>
  </head>
  <body onload="startGame()">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark text-white h-50">
        <div class="container-fluid hovered h-50">
          <a class="navbar-brand" href="index.php">Solpadoin Mini-games</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-tabs nav-pills rounded-pill">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#">Features</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Pricing</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="#">Login</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Disabled</a>
              </li>
            </ul>
            <form class="d-flex">
              <div class="ui-widget">
                <input class="form-control me-2" id="tags" type="search" placeholder="Search" aria-label="Search">
              </div>
            </form>
          </div>
        </div>
      </nav>
      <hr/>
      <div class="p-3 mb-2 text-dark" id="parent">
        <p id="values"></p>
        <div id="game">
          <br>
          <button onmousedown="restartGame()">RESTART</button>
          <p>Use the Space button to push your velocity up.</p>
          <p>Z - Turn on shield</p>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid bg-dark">
          <p class="text-white">Fun website with minigame Done by Solpadoin ;)</p>
        </div>
      </footer>
  </body>
</html>