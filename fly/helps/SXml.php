<?php
namespace fly\helps;
class SXml
{
    public static function toArray(\SimpleXMLElement $xml)
    {
        $arXML = array(
            'name' => trim($xml->getName()),
            'value' => trim((string)$xml),
            'attr' => array(),
            'children' => array()
        );
        foreach ($xml->attributes() as $name => $value) {
            $arXML['attr'][$name]=trim($value);
        }
        
        foreach ($xml->children() as $name => $xmlchild) {
            $arXML['children'][$name]=static::toArray($xmlchild);
        }
        return $arXML;        
    }
    
    public static function toJson(\SimpleXMLElement $xml)
    {
        return json_encode(static::toArray($xml));
    }
    
    public static function merge(\SimpleXMLElement &$simplexml_to, \SimpleXMLElement &$simplexml_from)
    {
        static $firstLoop=true;        
        if ($firstLoop) {
            foreach ($simplexml_from->attributes() as $attr_key => $attr_value) {
                $simplexml_to->addAttribute($attr_key, $attr_value);
            }
        }
        foreach ($simplexml_from->children() as $simplexml_child) {
            $simplexml_temp = $simplexml_to->addChild($simplexml_child->getName(), (string) $simplexml_child);
            
            foreach ($simplexml_child->attributes() as $attr_key => $attr_value) {
                $simplexml_temp->addAttribute($attr_key, $attr_value);
            }
            $firstLoop=false;
            static::merge($simplexml_temp, $simplexml_child);
        }
        $firstLoop = false;
    }
    
    public static function plus(\SimpleXMLElement &$simplexml_to, \SimpleXMLElement &$simplexml_from, $xpath = null)
    {
        $xpathAux = '';
        foreach ($simplexml_from->children() as $simplexml_child) {
            $xpathAux = (($xpath)?($xpath.'/'):'').$simplexml_child->getName();
            $r = $simplexml_to->xpath($xpathAux);
            if (empty($r)) {
                $r = ($xpath)?$simplexml_to->xpath($xpath):array();
                if (!empty($r)) {
                    $simplexml_to = $r[0];
                }
                $simplexml_temp = $simplexml_to->addChild($simplexml_child->getName(), (string) $simplexml_child);
                foreach ($simplexml_child->attributes() as $attr_key => $attr_value) {
                    $simplexml_temp->addAttribute($attr_key, $attr_value);
                }
            } else {
                $simplexml_temp = $simplexml_to;
            }
            static::plus($simplexml_temp, $simplexml_child, $xpathAux);
        }
    }
}
