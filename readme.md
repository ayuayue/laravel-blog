#### laravel 5.5 简单文章管理demo

1. 创建项目 
```bash
composer create-project laravel/laravel --prefer-dist "5.5.*"
```
2. 修改 `.env` 文件.配置数据库.并创建数据库
3. 创建用户脚手架
```bash
php artisan make:auth
```
4. 生成文章迁移文件及资源控制器
```bash
php artisan make:model Post -m // -m 生成迁移文件跟model文件
php artisan make:controller PostController --resource // --resource 生成资源控制器
```
5. 迁移数据库
```bash
php artisan migrate
```
6. 使用 `factory` 填充数据
```bash
php artisan make:factory PostsFactory -m Post // -m 指定模型类名
```
6-1. 编辑 `PostsFactory` 文件, 填充数据字段及数据

```php

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'published_at' => \Carbon\Carbon::now(),
        'author_id' => 1
    ];
});

```
7. 使用 `tinker` 执行数据填充
**注意事项如果更改过表结构或者加入了 `setter` `getter`,或更改了配置,可能会导致某个字段导入失败,请检查字段填充的数据类型.** 
```bash
php artisan tinker // 进入 tinker,一下为命令

>>> namespace App; //设置命名空间
>>> factory(Post::class,20)->create(); // 创建数据
```
8. 配置批量赋值
```php
Post.php

protected $fillable = [
        'title','body','author_id','published_at'
    ];
```
9. 时间格式化为 `Carbon` 格式
```php
protected $dates = ['published_at'];
```
10. `setter`
```php
public function setPublishedAtAttribute($date)
{
    $this->attributes['published_at'] = Carbon::createFromFormat('Y-m-d',$date);
}
```

----
其他使用方法
1. 查找失败直接返回错误页面
```php
Post::findOrFail();
```
2. 返回最近时间排序
```php
Post::latest();
```
3. 分页
```php
Post::paginate(15); //默认15
// blade 
{{ $posts->links() }}
```
4. 获取当前登录用户 `id`
```php
user Illuminate\Support\Facades\Auth;
$id = Auth::id();
```
5. 参数中直接注入一个类
```php
// 默认通过 `id`
GET http://localhost/users/1 HTTP/1.1
Route::get('users/{user}', function(\App\User $user) {
    dd($user);
});

// 通过其他字段绑定,模型中定义方法
public function getRouteKeyName(){
    return 'name';
}
GET http://localhost/users/admin HTTP/1.1
Route::get('users/{user}', function(\App\User $user) {
    dd($user);
});
```
6. 类中定义中间件
```php
// 类的构造方法中定义, except 不通过中间件的控制器方法
public function __construct()
{
    $this->middleware('auth')->except(['index','show']);
}
```