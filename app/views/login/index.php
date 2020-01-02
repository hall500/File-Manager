<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= URL_ROOT ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ERoom - Login</title>

    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <div class="img-container">
        <img src="https://www.dextrongroup.net/img/main_logo.svg" alt="" height="120" width="300">
    </div>

    <div class="logo-container">
        <img src="./img/e_roomlogo.png" alt="" height="90" width="225">
    </div>

    <div class="form-container">
        <form id="app-login" class="form-content">
            <div id="error" style="padding: 10px; text-align: center"></div>
            <label for="email">Email </label>
            <input type="text" class="form-control" placeholder="Enter email" id="email" name="email">
            <label for="password">Password </label>
            <input type="password" class="form-control" placeholder="Enter password" id="password" name="password">
            <button id="login-btn" class="btn-primary"> Login </button>
        </form>

    </div>

    <script src="./js/common_func.js"></script>
    <script type="text/javascript">
    const login = () => {
        event.preventDefault();
        const form = document.querySelector('#app-login');
        if (form === null || form === undefined) return false;
        const formData = serialize(form);

        const errorContainer = document.querySelector('#error');
        const url = './login';
        post(url, formData).then(json => {
            errorContainer.innerHTML =
                '<i style="color: white; background-color: red; padding: 10px;">' +
                json.message + '</i>';
            setTimeout(() => {
                errorContainer.innerHTML = '';
            }, 2000);
            if (json.status === 'true') {
                window.location.href = json.link || url;
            }
        });
    };

    const btn = document.querySelector('#login-btn');
    btn.addEventListener('click', login, false);
    </script>
</body>

</html>