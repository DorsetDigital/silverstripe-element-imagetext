<?php
namespace DorsetDigital\Elements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\FieldType\DBField;


class ImageTextElement extends BaseElement
{

    private static $singular_name = 'Text & Image Block';
    private static $plural_name = 'Text & Image Blocks';
    private static $description = 'Adds a block of text with accompanying image';
    private static $table_name = 'DorsetDigital_Elements_ImageText';
    private static $db = [
        'Content' => 'HTMLText',
        'ImagePosition' => 'Varchar(10)',
        'ImageAlt' => 'Varchar(255)',
        'ImageWidth' => 'Varchar(10)'
    ];
    private static $many_many = [
        'Image' => Image::class
    ];
    private static $owns = [
        'Image'
    ];
    private static $inline_editable = false;
    private static $has_one = [
        'ImageLink' => SiteTree::class
    ];

    private static $sizes = [
        'half' => '1/2 page width',
        'third' => '1/3 page width',
        'quarter' => '1/4 page width',
        'sixth' => '1/6 page width'
    ];


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldsToTab('Root.Main', [
            HTMLEditorField::create('Content'),
            UploadField::create('Image')
                ->setAllowedFileCategories('image/supported')
                ->setFolderName('pageimages')
                ->setAllowedMaxFileNumber(1),
            DropdownField::create('ImagePosition')
                ->setSource([ 'after' => 'After Content', 'before' => 'Before Content' ]),
            TextField::create('ImageAlt')->setTitle('Alt text for the image'),
            DropdownField::create('ImageWidth')
                ->setSource($this->config()->get('sizes'))
                ->setDescription('Relative image size on larger screens.  On smaller screens the image will flow before or after the text content, instead of sitting next to it.'),
            TreeDropdownField::create('ImageLinkID',
                _t(__CLASS__ . '.Link', 'Link'),
                SiteTree::class
            )->setDescription(_t(__CLASS__ . '.LinkDescription', 'Optional link for the image'))
        ]);
        return $fields;
    }

    public function getType()
    {
        return 'Image & Text';
    }


}
