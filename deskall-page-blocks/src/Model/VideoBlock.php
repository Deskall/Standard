<?php


use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\ORM\FieldType\DBField;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\FieldType\DBHTMLText;
use g4b0\SearchableDataObjects\Searchable;
use Embed\Adapters\Adapter;
use Embed\Embed;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class VideoBlock extends BaseElement implements Searchable
{

	private static $inline_editable = false;
    
    private static $icon = 'font-icon-block-media';
    
    private static $controller_template = 'BlockHolder';

    private static $controller_class = BlockController::class;

    private static $table_name = 'VideoBlock';

    private static $singular_name = 'Videogalerie';

    private static $plural_name = 'Videogalerien';

    private static $description = 'Video Karousel';

    private static $db = [
        'HTML' => 'HTMLText',
        'Videos' => 'Text',
        'VideosHTML' => 'HTMLText',
        'VideoPerLine' => 'Varchar(255)',
        'Autoplay' => 'Boolean'
    ];

    private static $has_many = ['VideoObjects' => VideoObject::class];

    private static $owns = ['VideoObjects'];

    private static $defaults = [
        'Layout' => 'carousel',
        'VideoPerLine' => 'uk-child-width-1-2@s'
    ];

    private static $block_layouts = [
        'carousel' => 'Carousel',
        'grid' => 'Grid'
    ];

    private static $videos_per_line = [
        'uk-child-width-1-1' => '1',
        'uk-child-width-1-2@s' => '2',
        'uk-child-width-1-3@s' => '3'
    ];


    /**
     * Color to customize the vimeo player.
     * Can be set via config.yml
     * @var string
     */
    private static $player_color = '44BBFF';

    public function fieldLabels($includerelation = true){
        $labels = parent::fieldLabels($includerelation);
        $labels['Videos'] = 'URLs der extern Videos (1 per Linie)';

        return $labels;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeByName('VideoPerLine');
        $fields->removeByName('VideoPerLine');
        $fields->removeByName('Autoplay');
        $fields->removeByName('Layout');
        $fields->removeByName('VideoObjects');
          
            $fields
                ->fieldByName('Root.Main.HTML')
                ->setTitle(_t(__CLASS__ . '.ContentLabel', 'Inhalt'))
                ->setRows(5);
   
            $fields->addFieldToTab('Root.LayoutTab',CompositeField::create(
                CheckboxField::create('Autoplay',_t('VideoBlock.Autoplay','Video automatisch spielen?')),
                DropdownField::create('VideoPerLine',_t(__CLASS__.'.VideoPerLine','Videos per Linie'), $this->getTranslatedSourceFor(__CLASS__,'videos_per_line')),
                OptionsetField::create('Layout','Format', $this->getTranslatedSourceFor(__CLASS__,'block_layouts'))
            )->setTitle(_t(__CLASS__.'.BlockLayout','Layout'))->setName('BlockLayout'));
        
        $config = GridFieldConfig_RecordEditor::create();
            $config->addComponent(new GridFieldOrderableRows('Sort'));
            if (singleton('VideoObject')->hasExtension('Activable')){
                 $config->addComponent(new GridFieldShowHideAction());
            }
            $videosField = new GridField('VideoObjects',_t(__CLASS__.'.Videos','Video Dateien'),$this->VideoObjects(),$config);
            $fields->addFieldToTab('Root.Main',$videosField);
        
        return $fields;
    }

    public function onBeforeWrite(){
        // if ($this->isChanged('Videos')){
            $this->updateEmbedHTML();
        // }
        parent::onBeforeWrite();
    }

    //Videos
    /**
     * @return $this
     */
    public function Embed()
    {
        $this->setFromURL($this->SourceURL);

        return $this;
    }


    public function updateEmbedHTML()
    {
      $content = null;
      if ($this->Videos){
        $content = '';
        foreach (preg_split('/\r\n|[\r\n]/', $this->Videos) as $url){
            $html = $this->setFromURL($url);
            if ($html){
                $html = str_replace('?feature=oembed','?feature=oembed&rel=0',$html);
                $content .= ($this->Layout == "carousel") ? '<li class="uk-height-1-1">' : '<div>';
                $html = str_replace('<iframe','<iframe',$html);
                $content .= $html;
                $content .= ($this->Layout == "carousel") ? '</li>' : '</div>';
            }
        }
      }
      $this->VideosHTML = $content;
    }

    /**
     * @param $url
     */
    public function setFromURL($url)
    {
        if ($url) {
            // $config = array('min_image_width' => 1200, 'min_image_height' => 450);
            $info = Embed::create($url);
            $embed = $this->setFromEmbed($info);
            return $embed;
        }
    }

    /**
     * @param Adapter $info
     */
    public function setFromEmbed(Adapter $info)
    {
        $embed = $info->getCode();
        return $embed;
    }

    public function activeVideos(){
        return $this->VideoObjects()->filter('isVisible',1)->sort('Sort');
    }

      

   

    // public function getThumbnailURL( $url ){
    //  $media =  $this->Media($url);
    //  $ThumbnailUrl = ($media) ? $media->thumbnail_url : false;
    //  return $ThumbnailUrl;
    // }

    // function GetVideoThumbs(){
    //  $content = '';
    //  if( $this->countVideos() < 2){
    //      $thumbnail = $this->getThumbnailURL(trim($this->VideoList));
    //      if( $thumbnail ){
    //          $content .= '<img src="'.$thumbnail.'" class="img-full"/>';
    //      }
    //  }else{
    //      $count = 0;
    //      foreach (explode("\n",$this->VideoList) as $url){
    //          $thumbnail = $this->getThumbnailURL(trim($url));
    //          if( $thumbnail ){
    //              $content .= '<img src="'.$thumbnail.'" class="img-left"/>';
    //          }
    //          $count++;
    //          if( $count == 2 ){
    //              break;
    //          }
    //      }
    //  }

    //  return $content;
    // }

    // public function Videos(){
    //  $content = '';
    //  foreach (explode("\n",$this->VideoList) as $url){
    //     //youtube
    //      preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
    //      if ($matches[1]){
    //         $content = $content.'<li><iframe class="uk-height-1-1 uk-width-1-1" src="https://www.youtube-nocookie.com/embed/'.$matches[1].'?autoplay=0&amp;showinfo=0&amp;rel=0&amp;modestbranding=1&amp;playsinline=1" frameborder="0" allowfullscreen></iframe></li>';
    //      }

        
    //  }
    //  $output = new DBHTMLText();
    //  $output->setValue($content);
    //  return $output;    
    // }

    // function getVideos(){
    //  $videos = '';
    //  if ($this->VideoList != ""){
    //      foreach (explode("\n",$this->VideoList) as $url){
    //          $videoObject = $this->Media(trim($url));
    //          if( $videoObject ){
    //              $videos .= '<li class="uk-height-1-1">'.$videoObject->code.'</li>';
    //          }
    //      }
    //  }
        
    //  $html = DBHTMLText::create();
    //  $html->setValue($videos);
    //  return $html;
    // }

    // public function Media($url) {
    //  return Embed::create($url);
    // }

     public function getSummary()
    {
        return DBField::create_field('HTMLText', $this->HTML)->Summary(20);
    }

    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Videogalerie');
    }
    /************* TRANLSATIONS *******************/
    public function provideI18nEntities(){
        $entities = [];
        foreach($this->stat('block_layouts') as $key => $value) {
          $entities[__CLASS__.".block_layouts_{$key}"] = $value;
        }
         foreach($this->stat('videos_per_line') as $key => $value) {
          $entities[__CLASS__.".videos_per_line_{$key}"] = $value;
        }
       
        return $entities;
    }

/************* END TRANLSATIONS *******************/

/************* SEARCHABLE FUNCTIONS ******************/


    /**
     * Filter array
     * eg. array('Disabled' => 0);
     * @return array
     */
    public static function getSearchFilter() {
        return array();
    }

    /**
     * FilterAny array (optional)
     * eg. array('Disabled' => 0, 'Override' => 1);
     * @return array
     */
    public static function getSearchFilterAny() {
        return array();
    }


    /**
     * Fields that compose the Title
     * eg. array('Title', 'Subtitle');
     * @return array
     */
    public function getTitleFields() {
        return array('Title');
    }

    /**
     * Fields that compose the Content
     * eg. array('Teaser', 'Content');
     * @return array
     */
    public function getContentFields() {
        return array('HTML');
    }

    
/************ END SEARCHABLE ***************************/
}