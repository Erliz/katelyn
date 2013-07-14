<?php
/**
 * @author: Stanislav Vetlovskiy
 * @data 07.01.13
 */
class M_UploadHandlerExtend extends UploadHandler
{
    private $content = false;

    function __construct()
    {
        $options = array(
            'upload_dir'     => ConfigPath::$real . ConfigPath::$photoTmp,
            'upload_url'     => ConfigPath::$url . 'files/photo/',
//            'upload_url'     => $this->get_full_url() . '/' . ConfigPath::$photoTmp,
            'orient_image'   => true,
            'image_versions' => array(
                ''          => array(
                    'max_width'    => 3320,
                    'max_height'   => 1080,
                    'jpeg_quality' => 100
                ),
                'thumbnail' => array(
                    'max_width'    => 264,
                    'max_height'   => 176,
                    'jpeg_quality' => 100
                )
            )
        );
        parent::__construct($options);
    }

    protected function generate_response($content, $print_response = true)
    {
        ob_start();
        $result = parent::generate_response($content, $print_response);
        ob_end_clean();

        $this->content = $result;

        return $result;
    }

    public function getContent()
    {
        return $this->content;
    }
}
