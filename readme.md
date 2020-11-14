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
4-1. 编辑迁移文件
```php
Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('body');
            $table->unsignedInteger('author_id');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
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
11. 表单验证
```php
# 控制器中
$request->validate([
    'title' => 'required|unique:posts|max:255',
    'body' => 'required|min:6',
    'published_at' => 'required'
]);
```
11-1. 自定义 `request` 类实现表单认证.防止控制器内表单验证规则过多 
```php
# 首先先创建一个 `request` 类
php artisan make:request PostRequset // 生成一个 `request` 文件夹下的 `PostRequest` 类
# authorize 方法验证该请求允许的用户.true则为全部用户可以使用
public function authorize()
{
    return true;
}
# rules 方法放置规则.
public function rules()
{
    return [
        'title' => 'required|unique:posts|max:255',
        'body' => 'required|min:6',
        'published_at' => 'required'
    ];
}
# 最后在控制器中替换 `Request` 类型声明为 `PostRequest`
use App\Http\Requests\PostRequest;
public function store(PostRequest $request)
{
    $datas = $request->all();
    $datas['author_id'] = Auth::id();
    Post::create($datas);
    return redirect('posts');
}
```
12. 更改验证信息为中文
12-1. 复制 `resource/lang/en` 文件夹到同目录,并改名为 `zh`,更改 `config/app.php` 中的 `locale = 'zh'`
12-2. 推荐的自定义验证信息方式
```php
# 打开 `zh/validation.php, 按如下更改`
'custom' => [
    'attribute-name' => [
        'rule-name' => 'custom-message',
    ],
    'name' => [
        'required' => '名称不能为空',
        'unique' => '名称已存在',
        'max' => '标题最大不能超过 :max 个字符'
    ],
    'title' => [
        'required' => '标题不能为空',
    ]
], 
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
    return view('user.edit',compacts('user'));
});

// 通过其他字段绑定,模型中定义方法
public function getRouteKeyName(){
    return 'name';
}
GET http://localhost/users/admin HTTP/1.1
Route::get('users/{user}', function(\App\User $user) {
    return view('user.edit',compacts('user'));
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