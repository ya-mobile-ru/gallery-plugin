<?php namespace Yamobile\Gallery\Components;

use Lang;
use Request;
use Cms\Classes\ComponentBase;
use SystemException;
use Yamobile\Gallery\Classes\ComponentHelper;

class GalleryDetails extends ComponentBase {
   public $record;
   public $items;
   public $margin;
   public $grid;
   public $first_image;
   public $last_image;
   public $renderPaginate;
   public $notFoundMessage;
   public $identifierValue;

   public function componentDetails() {
      return [
         'name'        => 'yamobile.gallery::lang.gallery_photo.info.name',
         'description' => 'yamobile.gallery::lang.gallery_photo.info.description'
      ];
   }

   public function defineProperties() {
      return [
         'items' => [
            'title'         => 'yamobile.gallery::lang.gallery_list.items.title',
            'description'   => 'yamobile.gallery::lang.gallery_list.items.description',
            'type'              => 'string',
            'validationPattern' => '^[0-9]*$',
            'validationMessage' => Lang::get('yamobile.gallery::lang.gallery_list.items.title') . 'yamobile.gallery::lang.gallery_list.group_name',
            'default'       => '30',
         ],
         'margin' => [
            'title'         => 'yamobile.gallery::lang.gallery_list.margin.title',
            'type'              => 'string',
            'validationPattern' => '^[0-9]*$',
            'validationMessage' => Lang::get('yamobile.gallery::lang.gallery_list.margin.title') .'yamobile.gallery::lang.gallery_list.group_name',
            'default'       => '10',
         ],
         'grid' => [
            'title'         => 'yamobile.gallery::lang.gallery_list.grid.title',
            'description'   => 'yamobile.gallery::lang.gallery_list.grid.description',
            'type'          => 'dropdown',
            'options'       => [
               '2'    => '2',
               '3'    => '3',
               '4'    => '4',
               '5'    => '5'
            ],
            'default'       => '3',
         ],
         'identifierValue' => [
            'title'       => 'yamobile.gallery::lang.gallery_photo.identifierValue.title',
            'description' => 'yamobile.gallery::lang.gallery_photo.identifierValue.description',
            'type'        => 'string',
            'default'     => '{{ :slug }}',
            'validation'  => [
               'required' => [
                  'message' => 'yamobile.gallery::lang.gallery_photo.identifierValue.message'
               ]
            ]
         ],
         'gallerystyle' => [
            'title'         => 'yamobile.gallery::lang.gallery_list.gallerystyle.title',
            'description'   => 'yamobile.gallery::lang.gallery_list.gallerystyle.description',
            'type'          => 'checkbox',
            'default'       => 1,
         ],
         'galleryscripts' => [
            'title'         => 'yamobile.gallery::lang.gallery_list.galleryscripts.title',
            'description'   => 'yamobile.gallery::lang.gallery_list.galleryscripts.description',
            'type'          => 'checkbox',
            'default'       => 1,
         ],
         'notFoundMessage' => [
            'title'       => 'yamobile.gallery::lang.gallery_photo.notFoundMessage.title',
            'description' => 'yamobile.gallery::lang.gallery_photo.notFoundMessage.description',
            'default'     => 'yamobile.gallery::lang.gallery_photo.notFoundMessage.default',
            'type'        => 'string',
            'showExternalParam' => false
         ]
      ];
   }

   public function onRun() {
      $this->identifierValue = $this->property('identifierValue');

      if ($this->property('gallerystyle')) {
         $this->addCss('assets/css/gallery.css');
      }
      if ($this->property('galleryscripts')) {
         $this->addJs('assets/js/frontscripts.js');
         $this->addCss('assets/css/fancybox-with-form.css');
      }

      $this->record = $this->loadRecord();
      $this->prepareVars($this->record);

   }

   protected function prepareVars($record) {
      $this->notFoundMessage = $this->property('notFoundMessage');
      $this->grid = $this->property('grid');
      $this->margin = $this->property('margin').'px';

      if((int)$this->property('items')) {
         $this->items = $this->property('items');
         $this->setPaginate($record,$this->items);
      } else {
         $this->first_image = 0;
         $this->last_image = count($record->photos);
      }
   }

   protected function setPaginate($record,$items) {
      $url_param = !empty(Request::query()) ? http_build_query(Request::query()) : null;
      $count = isset($record->photos) ? count($record->photos) : 0;

      $last_page = $items ? (int) ceil($count / $items) : 0;

      $url_param = parse_url($_SERVER['REQUEST_URI']);
      if(isset($url_param['query'])) {
         parse_str($url_param['query'], $params);
      }
      if(isset($params['page'])) {
         $current_page = (int) $params['page'];
         if($current_page>$last_page or !$current_page) {
            $current_page = 1;
         }
      } else {
         $current_page = 1;
      }

      $page_items = ($current_page*$items);

      $this->first_image = $page_items-$items;
      $this->last_image = $page_items;

      if($last_page > 1) {
         $paginateHTML =
         '<ul class="gallery-pagination">'.
         ComponentHelper::instance()->get_pagination_item($current_page,$last_page)
         .'</ul>';

         $this->renderPaginate = $paginateHTML;
      }
   }



   protected function loadRecord() {
      if (!strlen($this->identifierValue)) {return;}

      $modelClassName = 'Yamobile\Gallery\Models\GalleryModels';
      $model = new $modelClassName();

      return \Yamobile\Gallery\Models\GalleryModels::
         where('slug', '=', $this->identifierValue)
         ->first();
   }
}
