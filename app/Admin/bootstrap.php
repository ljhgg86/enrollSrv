<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Encore\Admin\Form::forget(['map', 'editor']);
// Encore\Admin\Admin::js('js/vue.js');
// Encore\Admin\Admin::js('js/vuex.js');
// Encore\Admin\Admin::js('https://unpkg.com/vue-router@2.0.0/dist/vue-router.js');
// Encore\Admin\Admin::js('https://unpkg.com/axios/dist/axios.min.js');
// Encore\Admin\Admin::css('https://unpkg.com/element-ui/lib/theme-chalk/index.css');
// Encore\Admin\Admin::js('https://unpkg.com/element-ui/lib/index.js');
// Encore\Admin\Admin::js('vendor/components/mylist.js');
// Encore\Admin\Admin::js('vendor/components/formitem.js');
// Encore\Admin\Admin::js('vendor/components/activity.js');
// Encore\Admin\Admin::js('vendor/components/start.js');

Encore\Admin\Admin::js('/js/start.js');
// Encore\Admin\Admin::css('/css/app.css');
