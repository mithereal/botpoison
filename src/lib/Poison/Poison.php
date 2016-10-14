<?php
/**
 * Created by PhpStorm.
 * User: mithereal
 * Date: 10/12/16
 * Time: 7:35 PM
 */

namespace Mithereal\Botpoison;


class Poison implements Poison_Interface
{

    /* @return property
     * @param string
     * Getter Function
     */
    public function get($var)
    {
    }

    /* @return property
     * @param property name
     * @param property value
     * Setter Function
     */
    public function set($var, $value)
    {
    }


    /* @return
     * run Function
     */
    public function activate()
    {

    }

    /* @return string
     * @param property object
     * @param property string
     * inject Function to insert poison into string
     */
    public function mix($view = null, $poison = self)
    {
        $dom = new DOMDocument();
        $document = null;
        $effects = $poison->activate();
        if ($view !== null) {

            $dom->loadHTML($view);
            $insert_location = $dom->getElementById('insert_location');
            $insert_location->parentNode->appendChild($effects);
            $insert_location->parentNode->removeChild($insert_location);
            $document = $dom->saveHTML();
        }
        return $document;
    }

    public function insert(DOMNode $parent, $source)
    {
        $tmpDoc = new DOMDocument();
        $tmpDoc->loadHTML($source);
        foreach ($tmpDoc->getElementsByTagName('body')->item(0)->childNodes as $node) {
            $node = $parent->ownerDocument->importNode($node);
            $parent->appendChild($node);
        }
    }

}