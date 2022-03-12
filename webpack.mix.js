const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.react('resources/js/app.js', 'public/js')
  .react('resources/js/components/Admin/App/index.js', 'public/js/admin-app.js')
  .react('resources/js/components/Admin/App/app-detail-index.js', 'public/js/admin-app-detail.js')
  .react('resources/js/components/Admin/App/featured-app-index.js', 'public/js/admin-featured-app.js')

  .react('resources/js/components/Admin/Page/index.js', 'public/js/admin-page.js')
  .react('resources/js/components/Admin/UserMgt/index.js', 'public/js/admin-usermgt.js')
  .react('resources/js/components/Admin/Ads/index.js', 'public/js/admin-ads.js')
  .react('resources/js/components/Admin/Configuration/Seo/index.js', 'public/js/admin-configuration-seo.js')
  .react('resources/js/components/Admin/Configuration/Analytics/index.js', 'public/js/admin-configuration-analytics.js')
  .react('resources/js/components/Admin/Configuration/App/index.js', 'public/js/admin-configuration-app.js')
  .react('resources/js/components/Admin/Configuration/Authentication/index.js', 'public/js/admin-configuration-authentication.js')
  .react('resources/js/components/Admin/Configuration/Registration/index.js', 'public/js/admin-configuration-registration.js')
  .react('resources/js/components/Admin/Configuration/General/index.js', 'public/js/admin-configuration-general.js')
  .react('resources/js/components/Admin/Category/index.js', 'public/js/admin-category.js')
  .react('resources/js/components/Admin/Setup/Slider/index.js', 'public/js/admin-setup-slider.js')
  .react('resources/js/components/Admin/App/app-store-index.js', 'public/js/admin-app-store.js')
  ;
   //.sass('resources/sass/app.scss', 'public/css');




 mix.sass('resources/sass/dcm.scss', 'public/css')
   /* CSS */

   // profile
   .sass('resources/sass/custom/profile/profile.scss', 'public/css')
   .sass('resources/sass/custom/codemirror/codemirror-custom.scss', 'public/css')
   .sass('resources/sass/dcm/themes/green.scss', 'public/css/themes/')

   /* JS */
   .js('resources/js/dcm.js', 'public/js')
   .js('resources/js/profile.js', 'public/js')
   .js('resources/js/admin/page.js', 'public/js/admin')
   .js('resources/js/admin/usermgt.js', 'public/js/admin')
   .js('resources/js/admin/ads.js', 'public/js/admin')
   .js('resources/js/admin/category.js', 'public/js/admin')

    // chartjs
   .js('resources/js/common/chart.min.js', 'public/js/common')
   .js('resources/js/common/readmore.js', 'public/js/common')
   .js('resources/js/common/menu-editor.min.js', 'public/js/common')

/* Tools */
.browserSync(
   {
         proxy: 'v2-googelplayappstore.test',
         files: [

           // =====================================================================
           // You probably need only one of the below lines, depending
           // on which platform this project is being built upon.
           // =====================================================================
           'public',          // Generic .html and/or .php files [no specific platform]
           'resources', // Laravel-specific view files
         ]
       })
.disableNotifications()

/* Options */
.options({
   processCssUrls: false,
});

