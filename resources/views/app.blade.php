<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>
    </head>
    <body>
        <div id="app">
            <q-layout view="hHh lpr fFf">
                <q-header>
                    <q-toolbar>
                        <q-toolbar-title>
                            Passport Programming Challenge
                        </q-toolbar-title>
                    </q-toolbar>
                </q-header>
                <q-page-container>
                    <q-page>
                        <app></app>
                    </q-page>
                </q-page-container>
            </q-layout>
        </div>
    </body>

    <script src="{{ asset('js/app.js') }}"></script>

</html>
