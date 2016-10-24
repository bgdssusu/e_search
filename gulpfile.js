var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.sass('app.scss')
        .version([
            'public/css/app.css'
        ]);
});