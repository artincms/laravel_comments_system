# laravel_comments_system
laravel comments system is a laravel package .in frontend template use vue js that provides user interfaces inviroment.  .

# Requiments 
<ul>
<li>
PHP >= 7.0
</li>
<li>
Laravel 5.5|5.6
</li>
</ul>

# Installation
<h3>Quick installation</h3> 
<div class="highlight highlight-source-shell"><pre>composer require artincms/laravel_comment_system</pre></div>
<h6>publish vendor</h6>
 <div class="highlight highlight-text-html-php"><pre>
 $ php artisan vendor:publish --provider=ArtinCMS\LCS\LCSServiceProvider
</pre> </div>
if update package for publish vendor you should run : 
 <div class="highlight highlight-text-html-php"><pre>
 $ php artisan vendor:publish --provider=ArtinCMS\LCS\LCSServiceProvider --force
</pre> </div>
<h6>migrate tabales</h6>
<div class="highlight highlight-text-html-php">
    <pre>
    $ php artisan migrate
</pre> </div>

<h3>publish seed</h3>
```apple js
 php artisan db:seed --class="ArtinCMS\LCS\Database\Seeds\LmmMorphableTableSeeder"
```
in all category you want use laravel comment system you should fill lmm_morphable table .
forexample if you want use this package for article you should fill lmm_morphable as bellow :
<table>
<thead>
<tr>
<th>pck_name</th>
<th>dev_name</th>
<th>name</th>
<th>model_name</th>
<th>target_column_name</th>
<th>target_column_alias</th>
<th>generate_url_func</th>
</tr>
</thead>
<tbody>
<tr>
<td>article</td>
<td>article_model</td>
<td>مقالات</td>
<td>App\Article</td>
<td>title</td>
<td>عنوان</td>
<td>comment_url</td>
</tr>
</tbody>
</table>
 <h1>usage</h1> 
 for use this package you should use bellow helper function anywhere in your project such as in your controller . 
    this helper function is :
    <h5>create html modal for show Comment manager in backed</h5>
    <div class="highlight highlight-text-html-php">
    <pre>
     LCS_createBackendCommentHtml()
</pre> </div>
<h5>use Comment Template in frontend</h5>
laravel comment system use vue.js for show  template in frontend .
for use comment Template in your page (for example your article page) you shold insert bellow component in anywhere you want .

 <div class="highlight highlight-text-html-php">
 
```
 <div id="lcs_comment">
        <laravel_comment_system target_model_name="item_model" target_id="{{LCS_getEncodeId(1)}}" target_parent_column_name="encode_parent_id"  :rtl=true :jalali_data=true></laravel_comment_system>
    </div>

```
</div>
that target model is model you want to connect to comment and id is database id of element and 'target_parent_column_nam' is name of parrent in database .

<h3>Config Parameter </h3>
this package has a config file in config/laravel_comment_system.php
that provide information for package . in continue we review some parameter
in the package :
<h4>auto_publish</h4>
if auto_publish is true every comment show in frontend and if false just
approved comment show 
<h4>guest_can_comments</h4>
if you want just loggin user can comment set this parameters true .
<h4>show_comment_item</h4>
in laravel comment system backend you can define opinion poll for each item
for show this opinion poll for items you should set this parameters to true .
<h4>user_data</h4>
for get user data you should define helper and set helper name in this config 
in bellow we suggest example of helper :
```apple js
function LCS_GetUserInformation($user_id)
{
    $user = \Illuminate\Foundation\Auth\User::find($user_id);
    return [
        'name' => $user->name,
        'email' => $user->email,
    ] ;
}
```