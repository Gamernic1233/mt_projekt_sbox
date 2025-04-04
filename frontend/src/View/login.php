<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>REGISTRACIA</title>
        <link rel="stylesheet" type="text/css" href="../css/login.css">
    </head>

    <body>
        <div class="formular">
            <div class="container">
                <div class="nadpis">Prihlasenie do uctu v S-BOX</div>

                <div class="input-group">
                    <div class="input-field">
                        <img src="../img/UserIcon.png" alt="Ikona">
                        <input type="text" placeholder="Zadaj username" id="login_input_password">
                    </div>
        
                    <div class="input-field">
                        <img src="../img/UserLockIcon.png" alt="Ikona">
                        <input type="text" placeholder="Zadaj heslo" id="login_input_username">
                    </div>
                    
                </div>

                <div class="box-group">
                    <input type="checkbox" id="show-password"> Zobraziť heslo
                </div>

                <div class="debug-msg-group">
                    <p class="error" id="error_msg"></p>
                    <p class="success" id="success_msg"></p>
                </div>

                <button class="login-button" id="login_button">
                    PRIHLASIT SA
                </button>

            </div>

            <a href="/register">Nemas este vytvoreny ucet ? Zaregistruj sa</a>
        </div>

        <script src="../js/login.js"></script>
    </body>
</html>