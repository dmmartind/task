<html>

<head>
    <title>My Todo List</title>
    <meta name="csrf-token" content="<?php csrf_token()?>">
    <link rel="stylesheet" href="{{asset('frontend/assets/css/task.css')}}">
</head>

<body>
<nav class="header">
    <div class="logo">TaskManager</div>
    <div class="header-right">
        <div class="username" style="font-size: 1rem;"><?php Auth::user()->name ?></div>
        <a class="active" onclick="profile.edit">Profile</a>
        <form id="logout-form" action="logout.php" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        <a class="#contact" href="logout.php"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>

    </div>
</nav>

<section id="todoapp">
    <header id="header">
        <h1>My Todo List</h1>
        <input id="new-todo" placeholder="What needs to be done?" autofocus>
    </header>
    <section id="main">
        <input id="toggle-all" type="checkbox">
        <label for="toggle-all">Mark all as complete</label>

        <ul id="todo-list"></ul>
    </section>
    <footer id="footer">
    </footer>
</section>
<footer id=info>
    <p>To-List App by David Martin</p>
</footer>
<script src="frontend/assets/js/jquery-3.6.0.min.js"></script>
<script src="frontend/assets/js/task.js"></script>
</body>

</html>

