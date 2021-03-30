<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css">

    <title>Web Game</title>

    <style>
      body {
          margin-bottom: 60px; /* footer height */
      }

      .footer {
          position: absolute;
          bottom: 0;
          height: 30px;
          width: 100%;
          background-color:lightsteelblue;
      }

      canvas {
          border:1px solid #d3d3d3;
          background-color: #f1f1f1;
      }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <style>
      .ui-widget-content {
          background:black;
      }
    </style>

    <script>   
          $( function() {
          var availableTags = [
            "Dimon is a bro",
            "Dimon is a druk",
            "Sanya kracava",
            "Bodia Good",
            "Vladosik Nice",
            "Vladosik Krut"
          ];

          $( "#tags" ).autocomplete({
            source: availableTags,
            focus:  displayItem,
            select: displayItem,
            change: displayItem
          });

          function displayItem(event, ui) {
            $('#values').text(ui.item.label)
          }

          $( document ).ready(function() {
            $("canvas").appendTo("#game");
          });
        });
    </script>

  </head>
  <body onload="startGame()">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark text-white h-50">
        <div class="container-fluid hovered h-50">
          <a class="navbar-brand" href="#">The Game ;)</a>
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
        <script>
            var myGamePiece;
            var enemyPlayer = [];
            var myObstacles = [];
            var myScore;
            var refillPoint;
            var bossFight = false;

            function refillShield()
            {
                if (myGamePiece.enginePower > 0)
                { 
                    myGamePiece.shieldPower = myGamePiece.enginePower;
                    myGamePiece.enginePower = 0;
                }
            }

            function newRefillPoint(x, y)
            {
                refillPoint = new component(5, 5, "red", x, y);
            }

            function startGame() {
                myGamePiece = new component(30, 30, "green", 10, 120);
                myGamePiece.gravity = 0.05;
                myGamePiece.isArmored = false;
                myGamePiece.shieldPower = 1000;
                myGamePiece.enginePower = 1000;
                //newRefillPoint(100, 120);

                myScore = new component("16px", "Consolas", "black", 280, 40, "text");
                myGameArea.start();

                document.addEventListener('keydown', function(event) {
                  if (event.code == 'Space') {
                    accelerate(-0.01);
                  }

                  if (event.code == 'KeyZ') {
                    refillShield();
                  }
                });

                document.addEventListener('keyup', function(event) {
                  if (event.code == 'Space') {
                    accelerate(0.01);
                  }
                });
            }

            function resetPlayerData()
            {
                myGamePiece.type = null;
                myGamePiece.score = 0;
                myGamePiece.width = 30;
                myGamePiece.height = 30;
                myGamePiece.speedX = 0;
                myGamePiece.speedY = 0;    
                myGamePiece.x = 10;
                myGamePiece.y = 120;
                myGamePiece.gravity = 0;
                myGamePiece.gravitySpeed = 0;
                myGamePiece.gravityAcceleration = 0;
                myGamePiece.isArmored = false;
                myGamePiece.shieldPower = 1000;
                myGamePiece.enginePower = 1000;
            }

            var myGameArea = {
                canvas : document.createElement("canvas"),
                start : function() {
                    this.canvas.width = 800;
                    this.canvas.height = 300;
                    this.context = this.canvas.getContext("2d");
                    let parent = document.getElementById("parent");
                    parent.insertBefore(this.canvas, document.getElementById("game"));
                    this.frameNo = 0;
                    this.interval = setInterval(updateGameArea, 1);
                },
                clear : function() {
                    this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
                },
                restart : function(){
                    myObstacles.pop();
                    myGameArea.frameNo = 0;
                }
            }

            function restartGame()
            {
                myGameArea.clear();
                myGameArea.restart();
                resetPlayerData();
            }

            function component(width, height, color, x, y, type) {
                this.type = type;
                this.score = 0;
                this.width = width;
                this.height = height;
                this.speedX = 0;
                this.speedY = 0;    
                this.x = x;
                this.y = y;
                this.gravity = 0;
                this.gravitySpeed = 0;
                this.gravityAcceleration = 0;
                this.isArmored = false;
                this.shieldPower = 0;
                this.enginePower = 0;
                this.destroyed = false;

                this.update = function() {
                    ctx = myGameArea.context;
                    if (this.type == "text") {
                        ctx.font = this.width + " " + this.height;
                        ctx.fillStyle = color;
                        ctx.fillText(this.text, this.x, this.y);
                    } else {
                        ctx.fillStyle = color;
                        ctx.fillRect(this.x, this.y, this.width, this.height);
                        if (this.shieldPower > 0)
                        {
                            ctx.strokeRect(this.x - 10, this.y - 10, this.width + 20, this.height + 20);
                            this.isArmored = true;
                            this.shieldPower = this.shieldPower - 1;
                        }
                        else if (this.isArmored)
                            this.isArmored = false;
                    }
                }

                this.newPos = function() {
                    this.gravitySpeed += this.gravity;
                    this.x += this.speedX;
                    this.y += this.speedY + this.gravitySpeed;
                    this.hitBottom();
                    this.hitFloor();
                }

                this.hitFloor = function()
                {
                    var floor = 0;
                    if (this.y < floor) {
                        this.y = floor;
                        this.gravitySpeed = 0;
                    }
                }

                this.hitBottom = function() {
                    var rockbottom = myGameArea.canvas.height - this.height;
                    if (this.y > rockbottom) {
                        this.y = rockbottom;
                        this.gravitySpeed = 0;
                    }
                }
                
                this.crashWith = function(otherobj) {
                    var myleft = this.x;
                    var myright = this.x + (this.width);
                    var mytop = this.y;
                    var mybottom = this.y + (this.height);
                    var otherleft = otherobj.x;
                    var otherright = otherobj.x + (otherobj.width);
                    var othertop = otherobj.y;
                    var otherbottom = otherobj.y + (otherobj.height);
                    var crash = true;
                    if ((mybottom < othertop) || (mytop > otherbottom) || (myright < otherleft) || (myleft > otherright)) {
                        crash = false;
                    }
                    return crash;
                }
            }

            function getRandomInt(max) {
                return Math.floor(Math.random() * Math.floor(max));
            }

            function getRandomInt(min, max) {
                min = Math.ceil(min);
                max = Math.floor(max);
                return Math.floor(Math.random() * (max - min)) + min;
            }

            function changeMode(score)
            {
                if ((score / 1000) % 1 == 0) {
                    bossFight = !bossFight;
                }
            }

            function updateGameArea() {
                var x, height, gap, minHeight, maxHeight, minGap, maxGap;
                // If player hits obstacle.....
                for (i = 0; i < myObstacles.length; i += 1) {
                    if (myGamePiece.crashWith(myObstacles[i]))
                    {
                        if (myGamePiece.isArmored)
                            myObstacles[i].destroyed = true;
                        else
                            return;
                    }
                }

                for (i = 0; i < enemyPlayer.length; i += 1) {
                    if (myGamePiece.crashWith(enemyPlayer[i]))
                    {
                        return;
                    }
                }

                if (bossFight)
                { 
                    //myGamePiece.shieldPower = myGamePiece.shieldPower <= 0 ? 10000 : -1;
                    //myGamePiece.enginePower = 10000;
                }

                myGameArea.clear();
                myGameArea.frameNo += 1;
                let intervalLength = getRandomInt(200);

                if (myGameArea.frameNo == 1 || everyinterval(intervalLength) || (myGameArea.frameNo % 600) == 0) {
                    x = myGameArea.canvas.width;
                    minHeight = 20;
                    maxHeight = 100;
                    height = Math.floor(Math.random()*(maxHeight-minHeight+1)+minHeight);
                    minGap = 120;
                    maxGap = 200;
                    gap = Math.floor(Math.random()*(maxGap-minGap+1)+minGap);
                    myObstacles.push(new component(10, height, "blue", x, 0));
                    myObstacles.push(new component(10, x - height - gap, "blue", x, height + gap));
                    myObstacles.push(new component(x, 1, "blue", x, 0));

                    myGamePiece.enginePower = myGamePiece.enginePower + 5;
                }

                if ((myGameArea.frameNo % 1770) == 0)
                {
                    if (bossFight)
                    {
                        x = myGameArea.canvas.width;
                        minHeight = 20;
                        maxHeight = 100;
                        height = Math.floor(Math.random() * (maxHeight-minHeight+1) + minHeight);
                        minGap = 120;
                        maxGap = 200;
                        gap = Math.floor(Math.random()*(maxGap-minGap+1)+minGap);
                        enemyPlayer.push(new component(60, 30, "red", x, gap));
                    }
                    else
                    {
                        x = myGameArea.canvas.width;
                        minHeight = 20;
                        maxHeight = 100;
                        height = Math.floor(Math.random() * (maxHeight-minHeight+1) + minHeight);
                        minGap = 120;
                        maxGap = 200;
                        gap = Math.floor(Math.random()*(maxGap-minGap+1)+minGap);
                        enemyPlayer.push(new component(10, 10, "purple", x, gap));
                    }
                }

                let mass = 5;
                myGamePiece.gravity = myGamePiece.gravity + 0.000001 * (mass * 2) * 9.8;

                for (i = 0; i < myObstacles.length; i += 1) {
                    myObstacles[i].x += -1;
                    myObstacles[i].update();
                }

                for (i = 0; i < enemyPlayer.length; i += 1)
                {
                    if (bossFight)
                        enemyPlayer[i].x += -2;
                    else
                        enemyPlayer[i].x += -4;

                    enemyPlayer[i].update();
                }

                if (bossFight)
                    myScore.text = "BOSS FIGHT!! He is coming!";
                else
                    myScore.text = "SCORE: " + myGameArea.frameNo + " Engine: " + myGamePiece.enginePower + " Shield Power: " + myGamePiece.shieldPower;

                myScore.update();
                myGamePiece.newPos();
                myGamePiece.update();

                changeMode(myGameArea.frameNo);
            }

            function everyinterval(n) {
                if ((myGameArea.frameNo / n) % 1 == 0) {return true;}
                return false;
            }

            function accelerate(n) {
              if (myGamePiece.enginePower > 0)
              {
                  myGamePiece.gravity = myGamePiece.gravityAcceleration + n;
                  if (myGamePiece.gravity > myGamePiece.gravityAcceleration)
                  {
                    myGamePiece.gravity = myGamePiece.gravityAcceleration;
                  }
                  myGamePiece.enginePower = myGamePiece.enginePower - 1;
              }
            }
            </script>
        <div id="game">
          <br>
          <button onmousedown="restartGame()">RESTART</button>
          <p>Use the Space button to push your velocity up.</p>
          <p>Left click - shooting</p>
          <p>Z - Open shield</p>
        </div>
        <?php
            include "lib/qrlib.php";
            $score = 0;
            $outputStr = 'Your score: '.$score.' You are insane!!';
            QRcode::png($outputStr, 'image.png', 'L', 8, 4);
        ?>
      </div>
      <footer class="footer">
        <div class="container-fluid bg-dark">
          <p class="text-white">Fun website with minigame Done by Solpadoin ;)</p>
        </div>
      </footer>
  </body>
</html>