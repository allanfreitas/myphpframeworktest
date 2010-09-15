<?php
/**
 * SXml
 *  
 * PHP version 5.3
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @category   Helps
 * @package    Fly
 * @subpackage Helps
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2010 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    http://www.opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @version    SVN: $Id$
 * @link       http://www.mostofreddy.com.ar
 */
namespace fly\helps;
/**
 * SXml
 * 
 * Clase que brinda distintos features para el manejo de objetos SimpleXML
 * 
 * Features:
 * - toArray: Transforma un objeto SimpleXML a un array
 * - toJson: Transforma un objeto SimpleXML a JSON 
 * - merge: Realiza el merge entre dos objetos SimpleXML
 * - plus: Suma dos objets SimpleXML
 *  
 * @category   Helps
 * @package    Fly
 * @subpackage Helps
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2010 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    http://www.opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @version    Release: @package_version@
 * @link       http://www.mostofreddy.com.ar
 * @static
 */
class SXml extends \SimpleXMLElement 
{
    
    /**
     * Transforma un objeto SimpleXML a un array
     * 
     * @return array
     */
    public function toArray()
    {
        return $this->toArrayProc($this);
    }
    
    protected function toArrayProc(\SimpleXMLElement $xml)
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
            $arXML['children'][$name]=$this->toArrayProc($xmlchild);
        }
        return $arXML;
    }
    /**
     * Transforma un objeto SimpleXML a JSON 
     * 
     * @param \SimpleXMLElement $xml objeto a transformar
     * 
     * @return 
     * @static
     */
    public function toJson()
    {
        return json_encode($this->toArrayProc($this));
    }
    
    public function merge(\SimpleXMLElement &$sum)
    {
        return $this->mergeProc($this,$sum);
    }
    
    protected function mergeProc($to,$from)
    {
        foreach ($from->attributes() as $attr_key => $attr_value) {
            if (!isset($to[$attr_key])) {
                $to->addAttribute($attr_key, $attr_value);
            }
        }
        foreach ($from->children() as $child) {
            $temp = $to->addChild($child->getName(), (string) $child);
            $this->mergeProc($temp, $child);
        }
        return $to;
    }
    
    
    public function plus($plus)
    {
        return $this->plusProc($this,$plus);
    }
    
    protected function plusProc($to,$from)
    {
        foreach ($from->attributes() as $attr_key => $attr_value) {
            if (!isset($to[$attr_key])) {
                $to->addAttribute($attr_key, $attr_value);
            }
        }
        foreach ($from->children() as $child) {
            $n = $child->getName();
            if (isset($to->$n)) {
                $temp = $to->$n;
            } else {
                $temp = $to->addChild($child->getName(), (string) $child);
            }
            $this->plusProc($temp, $child);
        }
        return $to;
    }
    
    /**
     * Realiza el merge entre dos objetos SimpleXML
     * 
     * @param \SimpleXMLElement &$simplexmlTo   objeto en donde se agregaran los nuevos nodos del segundo objeto
     * @param \SimpleXMLElement &$simplexmlFrom objeto a agregar
     * 
     * @return void
     * @static
     */
    public static function merge2(\SimpleXMLElement &$simplexmlTo, \SimpleXMLElement &$simplexmlFrom)
    {
        static $firstLoop=true;        
        if ($firstLoop) {
            foreach ($simplexmlFrom->attributes() as $attr_key => $attr_value) {
                $simplexmlTo->addAttribute($attr_key, $attr_value);
            }
        }
        foreach ($simplexmlFrom->children() as $simplexml_child) {
            echo $simplexmlTo->getName().'<br/>';
            $simplexml_temp = $simplexmlTo->addChild($simplexml_child->getName(), (string) $simplexml_child);
            
            foreach ($simplexml_child->attributes() as $attr_key => $attr_value) {
                $simplexml_temp->addAttribute($attr_key, $attr_value);
            }
            $firstLoop=false;
            static::merge($simplexml_temp, $simplexml_child);
        }
        //$firstLoop = false;
    }
    /**
     * Suma dos objets SimpleXML
     * Los nodos del segundo objeto que se encuetren en el primero no son agregados. Tiene prioridad el primer objeto
     * 
     * @param \SimpleXMLElement &$simplexmlTo   objeto a cual se le sumara otro objeto SimpleXML
     * @param \SimpleXMLElement &$simplexmlFrom objeto a sumar
     * @param mixed             $xpath           xpath 
     * 
     * @return void
     * @static
     */
    public static function plus2(\SimpleXMLElement &$simplexmlTo, \SimpleXMLElement &$simplexmlFrom, $xpath = null)
    {
        $xpathAux = '';
        foreach ($simplexmlFrom->children() as $simplexml_child) {
            $xpathAux = (($xpath)?($xpath.'/'):'').$simplexml_child->getName();
            $r = $simplexmlTo->xpath($xpathAux);
            if (empty($r)) {
                $r = ($xpath)?$simplexmlTo->xpath($xpath):array();
                if (!empty($r)) {
                    $simplexmlTo = $r[0];
                }
                $simplexml_temp = $simplexmlTo->addChild($simplexml_child->getName(), (string) $simplexml_child);
                foreach ($simplexml_child->attributes() as $attr_key => $attr_value) {
                    $simplexml_temp->addAttribute($attr_key, $attr_value);
                }
            } else {
                $simplexml_temp = $simplexmlTo;
            }
            static::plus($simplexml_temp, $simplexml_child, $xpathAux);
        }
    }
}
