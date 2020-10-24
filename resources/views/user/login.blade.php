<form action="/user/login" method="post">
    @csrf
    用户名： <input type="text" name="user_name" placeholder="用户名/Email/手机号"><br>
    密码： <input type="password" name="user_pass1"><br>
    <input type="submit" value="登录">
</form>
