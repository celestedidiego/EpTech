<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Dancing+Script|Roboto);
        *, *:after, *:before {
            box-sizing: border-box;
        }

        body {
            background: rgba(4, 55, 79, 0.8); 
            /* background:  #281858; */
            text-align: center;
            font-family: 'Roboto', sans-serif;
        }

        .panda {
            position: relative;
            width: 200px;
            margin: 50px auto;
        }

        .face {
            width: 200px;
            height: 200px;
            background: #fff;
            border-radius: 100%;
            margin: 50px auto;
            box-shadow: 0 10px 15px rgba(0, 0, 0, .15);
            z-index: 50;
            position: relative;
        }

        .ear {
            position: absolute;
            width: 80px;
            height: 80px;
            background: #000;
            z-index: 5;
            border: 10px solid #fff;
            left: -15px;
            top: -15px;
            border-radius: 100%;
        }

        .ear:after {
            content: '';
            position: absolute;
            width: 80px;
            height: 80px;
            background: #000;
            z-index: 5;
            border: 10px solid #fff;
            left: 125px;
            top: -15px;
            border-radius: 100%;
        }

        .eye-shade {
            background: #000;
            width: 50px;
            height: 80px;
            margin: 10px;
            position: absolute;
            top: 35px;
            left: 25px;
            transform: rotate(220deg);
            border-radius: 25px / 20px 30px 35px 40px;
        }

        .eye-shade.rgt {
            transform: rotate(140deg);
            left: 105px;
        }

        .eye-white {
            position: absolute;
            width: 30px;
            height: 30px;
            border-radius: 100%;
            background: #fff;
            z-index: 500;
            left: 40px;
            top: 80px;
            overflow: hidden;
        }

        .eye-white.rgt {
            right: 40px;
            left: auto;
        }

        .eye-ball {
            position: absolute;
            width: 0px;
            height: 0px;
            left: 20px;
            top: 20px;
            max-width: 10px;
            max-height: 10px;
            transition: .1s;
        }

        .eye-ball:after {
            content: '';
            background: #000;
            position: absolute;
            border-radius: 100%;
            right: 0;
            bottom: 0px;
            width: 20px;
            height: 20px;
        }

        
        .nose {
            position: absolute;
            height: 20px;
            width: 35px;
            bottom: 40px;
            left: 0;
            right: 0;
            margin: auto;
            border-radius: 50px 20px/30px 15px;
            transform: rotate(15deg);
            background: #000;
        }
        

        .body {
            background: #fff;
            position: absolute;
            top: 200px;
            left: -20px;
            border-radius: 100px 100px 100px 100px / 126px 126px 96px 96px;
            width: 250px;
            height: 282px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, .3);
        }

        .hand {
            width: 40px;
            height: 30px;
            border-radius: 50px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, .15);
            background: #000;
            margin: 5px;
            position: absolute;
            top: 70px;
            left: -25px;
        }

        .hand:after, .hand:before {
            content: '';
            position: absolute;
            width: 40px;
            height: 30px;
            border-radius: 50px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, .15);
            background: #000;
            left: -5px;
            top: 11px;
        }

        .hand:before {
            top: 26px;
        }

        .hand.rgt {
            left: auto;
            right: -25px;
        }

        .hand.rgt:after, .hand.rgt:before {
            left: auto;
            right: -5px;
        }

        .foot {
            top: 360px;
            left: -80px;
            position: absolute;
            background: #000;
            z-index: 1400;
            box-shadow: 0 5px 5px rgba(0, 0, 0, .2);
            border-radius: 40px 40px 39px 40px / 26px 26px 63px 63px;
            width: 82px;
            height: 120px;
        }

        .foot:after {
            content: '';
            width: 55px;
            height: 65px;
            background: #222;
            border-radius: 100%;
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            margin: auto;
        }

        .finger {
            position: absolute;
            width: 25px;
            height: 35px;
            background: #222;
            border-radius: 100%;
            top: 10px;
            right: 5px;
        }

        .finger:after, .finger:before {
            content: '';
            position: absolute;
            width: 25px;
            height: 35px;
            background: #222;
            border-radius: 100%;
            right: 30px;
            width: 20px;
            top: 0;
        }

        .finger:before {
            right: 55px;
            top: 5px;
        }

        .foot.rgt {
            left: auto;
            right: -80px;
        }

        .foot.rgt .finger {
            left: 5px;
            right: auto;
        }

        .foot.rgt .finger:after {
            left: 30px;
            right: auto;
        }

        .foot.rgt .finger:before {
            left: 55px;
            right: auto;
        }

        form {
            display: none;
            max-width: 400px;
            padding: 20px 40px;
            background: #fff;
            height: 300px;
            margin: auto;
            display: block;
            box-shadow: 0 10px 15px rgba(0, 0, 0, .15);
            transition: .3s;
            position: relative;
            transform: translateY(-100px);
            z-index: 500;
            border: 1px solid #eee;
        }

        form.up {
            transform: translateY(-180px);
        }

        h1 {
            color: #829ef0;
            font-family: 'Dancing Script', cursive;
        }

        .btn {
            background: #fff;
            padding: 5px;
            width: 150px;
            height: 35px;
            border: 1px solid #4069ff;
            margin-top: 25px;
            cursor: pointer;
            transition: .3s;
            box-shadow: 0 50px #754cd4 inset;
            color: #fff;
        }

        .btn:hover {
            box-shadow: 0 0 #666 inset;
            color: #666;
        }

        .btn:focus {
            outline: none;
        }

        .form-group {
            position: relative;
            font-size: 15px;
            color: #666;
        }

        .form-group + .form-group {
            margin-top: 30px;
        }

        .form-label {
            position: absolute;
            z-index: 1;
            left: 0;
            top: -15px;
            transition: .3s;
        }

        .form-control {
            width: 100%;
            position: relative;
            z-index: 3;
            height: 35px;
            background: none;
            border: none;
            padding: 5px 0;
            transition: .3s;
            border-bottom: 1px solid #777;
            color: #555;
        }

        .form-control:invalid {
            outline: none;
        }

        .form-control:focus, .form-control:valid {
            outline: none;
            box-shadow: 0 1px #12008a;
            border-color:  #12008a;
        }

        .form-control:focus + .form-label, .form-control:valid + .form-label {
            font-size: 12px;
            color:  #12008a;
            transform: translateY(-15px);
        }

        .alert {
            position: absolute;
            color: #f00;
            font-size: 16px;
            right: -200px;
            top: -100px;
            z-index: 200;
            padding: 10px 13px;
            background: #fff;
            box-shadow: 0 3px 5px rgba(0, 0, 0, .2);
            border-radius: 50px;
            opacity: 0;
            transform: scale(0);
            transition: .4s .6s linear;
        }

        .alert:after, .alert:before {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            background: #fff;
            left: -19px;
            bottom: -8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .2);
            border-radius: 50%;
        }

        .alert:before {
            width: 15px;
            height: 15px;
            left: -35px;
            bottom: -25px;
        }

        .wrong-entry {
            animation: wrong-log 0.3s;
        }

        .wrong-entry .alert {
            opacity: 1;
            transform: scale(1);
        }

        @keyframes eye-blink {
            to {
                height: 30px;
            }
        }

        @keyframes wrong-log {
            0%, 100% {
                left: 0px;
            }
            20%, 60% {
                left: 20px;
            }
            40%, 80% {
                left: -20px;
            }
        }

        .white-text {
            color: white;
            text-decoration: none; /* Rimuove la sottolineatura */
        }

        .white-text:hover {
            text-decoration: underline; /* Aggiunge la sottolineatura al passaggio del mouse */
        }

        .card-footer {
        margin-top: -20px; /* Sposta il blocco più in alto */
        }

    </style>
</head>
<body>
    <div class="panda">
        <div class="ear"></div>
        <div class="face">
            <div class="eye-shade"></div>
            <div class="eye-white">
                <div class="eye-ball"></div>
            </div>
            <div class="eye-shade rgt"></div>
            <div class="eye-white rgt">
                <div class="eye-ball"></div>
            </div>
            <div class="nose"></div>
            <div class="mouth"></div>
        </div>
        <div class="body"></div>
        <div class="foot">
            <div class="finger"></div>
        </div>
        <div class="foot rgt">
            <div class="finger"></div>
        </div>
    </div>
    <form method="POST" action="/EpTech/user/login" data-error-log="{if $error_log == 1}true{else}false{/if}">
        <div class="hand"></div>
        <div class="hand rgt"></div>
        <h1>Login</h1>
        {if $error_log == 1}
        <div class="mt-5">
            <p class="alert">Invalid Credentials..!!</p>
        </div>
        {/if}  
        <div class="form-group">
            <input name="email-log" type="email" class="form-control" id="email" placeholder="prova@example.com" required="required">
            <label class = "form-label" for="email">Email</label>
        </div>
        <div class="form-group">
            <input name="password-log" type="password" class="form-control" id="password" placeholder="password" required="required">
            <label class = "form-label" for="password">Password</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
        
        
    </form>
    <div class="card-footer text-center">
        <a href="/EpTech/user/signUp" class="white-text">Non sei registrato? Registrati!</a>
    </div>
    <br>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            // Leggi il valore di data-error-log dal form
            var errorLog = $('form').data('error-log');

            // Controlla se c'è un errore di login
            if (errorLog) {
                $('form').addClass('wrong-entry');
                setTimeout(function() {
                    $('form').removeClass('wrong-entry');
                }, 3000);
            }

            // Quando si clicca su password si alza il form
            //$('#password').focusin(function(){
                //$('form').addClass('up');
            //});
            //$('#password').focusout(function(){
                //$('form').removeClass('up');
            //});

            // Panda Eye move
            $(document).on("mousemove", function(event) {
                var dw = $(document).width() / 15;
                var dh = $(document).height() / 15;
                var x = event.pageX / dw;
                var y = event.pageY / dh;
                $('.eye-ball').css({
                    width: x,
                    height: y
                });
            });
        });
    </script>
</body>
</html>