<?php
namespace app\util;

class Html
{

    private static $divStart       = '<div class="form-group row">';
    private static $smallDivStart  = '<div class="col-sm-4">';
    private static $divEnd         = '</div>';
    private static $smallHelpStart = '<small id="Help" class="form-text text-muted">';
    private static $smallHelpEnd   = '</small>';

    public static function buildHtml($value)
    {
        return self::$divStart .
        self::getLableTag($value) .
        self::$smallDivStart .
        self::buildHtmlType($value) .
        self::$smallHelpStart .
        self::getSmallHelpTag($value) .
        self::$smallHelpEnd .
        self::$divEnd .
        self::$divEnd;
    }

    // 组装输入框
    public static function buildHtmlType($value)
    {
        switch ($value['html_tag'])
        {

            case 'file':
                $tag = self::getHtmlFileTag($value);
                break;
            case 'textarea':
                $tag = self::getHtmlTextAreaTag($value);
                break;
            case 'select':
                $tag = self::getHtmlSelectTag($value);
                break;
            default:
                $tag = self::getHtmlInputTag($value);
                break;
        }
        return $tag;
    }

    public static function getHtmlSelectTag($value)
    {

        $testArr = explode(',', $value['option']);
        $str     = "<select class='custom-select' name=" . $value['category_name'] . ">";
        foreach ($testArr as $k => $v)
        {
            $selected = $k == $value['selected'] ? 'selected' : '';
            $str .= "<option value=" . $k . "  " . $selected . " >" . $v . "</option>";
        }
        $str .= "</select>";
        return $str;

    }

    /**
     * [getHtmlFileTag 上传tag]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function getHtmlFileTag($value)
    {
        $id = $value['category_name'];
        $fileId = 'File'.$value['category_name'];
        $valueId = 'Value'.$value['category_name'];

        return '<input type="file" name="file" id="'.$fileId.'" style="display: none;">
                    <input type="text" name="'.$id.'" id="'.$valueId.'" value="" style="display: none;">
                    <div id="'.$id.'" onclick="uploadImageById('.$id.','.$fileId.','.$valueId.')">
                        <img src="">
                    </div>';


        $content = !empty($value['content']) ? $value['content'] : 'http://edu.com/static/img/user3-128x128.jpg';
        $str     = "<input type='file'  name=image[] id=" . $value['category_name'] . ">";

        if ($content)
        {
            $str .= "<img src=" . $content . ">";
        }
        return $str;

    }

    // 组装展示字符串
    public static function getLableTag($value)
    {
        return "<label for=" . $value['category_name'] . " class='col-sm-1 col-form-label'> " . $value['title'] . "</label>";
    }

    // 组装input输入框
    public static function getHtmlInputTag($value)
    {
        $content = !empty($value['content']) ? $value['content'] : "''";
        return "<input type='text' class='form-control' name=" . $value['category_name'] . " id=" . $value['category_name'] . " value=" . $content . "  placeholder=请输入" . $value['title'] . ">";
    }

    // 组装文本框
    public static function getHtmlTextAreaTag($value)
    {
        $content = !empty($value['content']) ? $value['content'] : "''";

        return "<textarea  type='text' class='form-control' name=" . $value['category_name'] . " id=" . $value['category_name'] . " placeholder=请输入" . $value['title'] . " row='3'>" .$value['content']. "</textarea>";
    }

    /**
     * [getSmallHelpTag 获取帮助tag]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function getSmallHelpTag($value)
    {
        if ($value['small_help'])
        {
            return "<a href=" . $value['small_help'] . "target='_blank'>如何配置" . $value['title'] . "?</a>";
        }
        else
        {
            return '';
        }
    }

}
