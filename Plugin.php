<?php namespace Yamobile\Gallery;

use Backend;
use Schema;
use October\Rain\Database\Schema\Blueprint;
use System\Classes\PluginBase;

class Plugin extends PluginBase {
   public function pluginDetails() {
      return [
         'name'        => 'yamobile.gallery::lang.plugin.details.name',
         'description' => 'yamobile.gallery::lang.plugin.details.description',
         'author'      => 'Yamobile',
         'icon'        => 'icon-image'
      ];
   }

   public function register() {}

   public function boot() {}

   public function registerComponents() {
      return [
         'Yamobile\Gallery\Components\GalleryList' => 'galleryList',
         'Yamobile\Gallery\Components\GalleryDetails' => 'galleryDetails'
      ];
   }

   public function registerPermissions() {
    return [
        'yamobile.gallery.access_galleries' => [
            'label' => 'yamobile.gallery::lang.permissions.galleries',
            'tab' => 'Gallery',
            'order' => 200,
        ],
    ];
   }

   public function registerNavigation() {
      return [
         'gallery' => [
               'label'       => 'yamobile.gallery::lang.plugin.menu.name',
               'url'         => Backend::url('yamobile/gallery/Gallery'),
               'icon'        => 'icon-image',
               'permissions' => ['yamobile.gallery.access_galleries'],
               'order'       => 500,
         ],
      ];
   }
}
