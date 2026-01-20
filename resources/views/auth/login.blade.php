<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/login.css'])
</head>

<body>

<div class="login-card text-center">

    <h3>Login Admin</h3>

    @if(session('error'))
        <div class="error-box">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3 text-start">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3 text-start">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn login-btn w-100 py-2">Login</button>

    </form>

</div>

</body>
</html>
