const elixir = require("laravel-elixir");
const BrowserSync = require("laravel-elixir-browsersync2");

require("laravel-elixir-vue-2");

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir((mix) => {
  BrowserSync.init();
  mix
    .scripts(
      [
        "library/jquery.min.js",
        "library/idangerous.swiper.min.js",
        "library/global.js",
        "library/jquery.mousewheel.js",
        "library/jquery.jscrollpane.min.js",
      ],
      "../components/front/js/library.js"
    )
    .webpack("resources/assets/js/app.js", "../components/front/js/app.js")
    .sass(
      ["bootstrap.min.css", "idangerous.swiper.css", "style.css", "app.scss"],
      "../components/front/css/app.css"
    )
    .BrowserSync({
      proxy: "localhost:8000",
      injectChanges: false,
      files: ["../components/front/css/**", "../components/front/js/**"],
    });
});
