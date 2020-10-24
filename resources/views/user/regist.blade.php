@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="/user/regist" method="post">
    @csrf
    用户名： <input type="text" name="user_name"><br>
    Email： <input type="text" name="user_email"><br>
    手机号： <input type="text" name="user_mobile"><br>
    密码： <input type="password" name="pass"><br>
    确认密码： <input type="password" name="pass_confirmation"><br>
    <input type="submit" value="注册">
</form>
