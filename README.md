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
 
```html
<div id="comments">
    <laravel_comments_system :target_model_name="'App\\Article'" :target_id="1" :target_parent_column_name="'parent_id'" :user-id="{{LCS_getUserId()}}" :show="true" ></laravel_comments_system>
</div>

```
</div>
that target model is model you want to connect to comment and id is database id of element and 'target_parent_column_nam' is name of parrent in database .
