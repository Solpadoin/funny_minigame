	        var myGamePiece;
            var enemyPlayer = [];
            var myObstacles = [];
            var myScore;
            var refillPoint;
            var bossFight = false;
            var isNight = false;

            function refillShield()
            {
                if (myGamePiece.enginePower > 0)
                { 
                    myGamePiece.shieldPower = myGamePiece.enginePower;
                    myGamePiece.enginePower = 0;
                }
            }

            function changeTime()
            {
                if ((myGameArea.frameNo % 1500) == 0)
                    isNight = !isNight;
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
                    this.canvas.width  = 800;
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
                        ctx.fillStyle = isNight ? "black" : color;
                        ctx.fillText(this.text, this.x, this.y);
                    } else {
                        ctx.fillStyle = isNight ? "black" : color;
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

            function getRndInteger(min, max) {
                return Math.floor(Math.random() * (max - min + 1) ) + min;
            }

            function getRndFloat(min, max) {
                return Math.random() * (max - min + 1) + min;
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

            function everyinterval(n) {
                if ((myGameArea.frameNo / n) % 1 == 0) { return true; }
                return false;
            }

            function generateObstacle(frameTime)
            {
                let rand = getRndInteger(200, 600);
                if (frameTime == 1 || everyinterval(2500) || (frameTime % rand) == 0)
                return true;
                else return false;
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

                myGameArea.clear();
                myGameArea.frameNo += 1;

                if (generateObstacle(myGameArea.frameNo)) {
                    var rdgsdg = false;

                    x = myGameArea.canvas.width + 1500;
                    minHeight = 20;
                    maxHeight = 100;
                    height = Math.floor(Math.random()*(maxHeight-minHeight+1)+minHeight);
                    minGap = 110;
                    maxGap = 130;
                    gap = Math.floor(Math.random()*(maxGap-minGap+1)+minGap);

                    myObstacles.push(new component(10, height, "blue", x, 0));
                    myObstacles.push(new component(10, x - height + gap, "blue", x, height + gap));
                    myObstacles.push(new component(x, 1, "green", x, 0));
                    myObstacles.push(new component(x, 1, "green", x, myGameArea.canvas.height - 1));

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

                let mass = 2;
                myGamePiece.gravity = myGamePiece.gravity + 0.000001 * (mass * 2) * 9.8;

                for (i = 0; i < myObstacles.length; i += 1) {
                    myObstacles[i].x += -1 - 0.0001 * myGameArea.frameNo;
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
                changeTime();
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