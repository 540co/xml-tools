<?php
namespace FiveFortyCo;


use Goetas\XML\XSDReader\SchemaReader;
use Goetas\XML\XSDReader\Schema\Type\BaseComplexType;
use Goetas\XML\XSDReader\Schema\Type\ComplexType;
use Goetas\XML\XSDReader\Schema\Type\ComplexTypeSimpleContent;
use Goetas\XML\XSDReader\Schema\Type\SimpleType;
use Goetas\XML\XSDReader\Schema\Element\Group;
use Goetas\XML\XSDReader\Schema\Element\ElementRef;
use Goetas\XML\XSDReader\Schema\Attribute\Attribute;
use Goetas\XML\XSDReader\Schema\Attribute\AttributeSingle;
use Goetas\XML\XSDReader\Schema\Attribute\AttributeRef;
use Goetas\XML\XSDReader\Schema\Attribute\Group as AttributeGroup;

class Xmltools {

  public function getXmlArrays($tables) {
    $arrays = [];
    foreach ($tables as $table) {
      foreach($table['relationships'] as $rel) {
        $fullPath = $table['name'] . '/' . $rel['element'];
        $arrays[] = $fullPath;
      }
    }
    return $arrays;
  }
  public function getXsdDetails($file) {
    $tables = [];
    $reader = new SchemaReader();
    $schema = $reader->readFile($file);
    $rootElements = $schema->getElements();
    foreach ($rootElements as $rootElement) {
      self::visitRootElement($tables, $rootElement);
    }
    return $tables;
  }
  private function visitRootElement(&$tables, $rootElement) {
    $rootType = $rootElement->getType();
    $tables["//".$rootElement->getName()] = [
      'name' => "//".$rootElement->getName(),
      'columns' => [],
      'relationships' => [],
      'schemaType' => ''
    ];
    self::traverseType($tables, $rootType, "//".$rootElement->getName(), "//".$rootElement->getName());
  }
  private function getAllElements($type) {
    if ($type instanceof ComplexType) {
      $elements = $type->getElements();
      $extension = $type->getExtension();
      if ($extension) {
        $baseType = $extension->getBase();
        if ($baseType) {
          $elements = array_merge($elements, self::getAllElements($baseType));
        }
      }
    } else {
      $elements = [];
    }
    return $elements;
  }
  private function getAllAttributes($type) {
    if ($type instanceof BaseComplexType) {
      $attributes = $type->getAttributes();
      $extension = $type->getExtension();
      if ($extension) {
        $baseType = $extension->getBase();
        if ($baseType) {
          $attributes = array_merge($attributes, self::getAllAttributes($baseType));
        }
      }
    } else {
      $attributes = [];
    }
    return $attributes;
  }
  private function getTypeName($type) {
    $typeName = $type->getName();
    if (!$typeName) {
      $typeName = 'Anonymous';
    }
    // $restriction = $type->getRestriction();
    // if ($restriction) {
    //   $baseType = $restriction->getBase();
    //   if ($baseType) {
    //     $typeName .= ' subset of ' . getTypeName($baseType);
    //   }
    // }
    $extension = $type->getExtension();
    if ($extension) {
      $baseType = $extension->getBase();
      if ($baseType) {
        $typeName .= ' extends ' . self::getTypeName($baseType);
      }
    }
    return $typeName;
  }
  private function traverseType(&$tables, $type, $topParentName = '', $parentName = '') {
    $childArrays = [];
    $nonArrayItems = [];

    $elements = self::getAllElements($type);
    foreach ($elements as $element) {
      if ($element instanceof Group) {
        $groupElements = $element->getElements();
        foreach ($groupElements as $groupElem) {
          self::visitElement($tables, $groupElem, $topParentName, $parentName, $childArrays, $nonArrayItems);
        }
      } else {
        self::visitElement($tables, $element, $topParentName, $parentName, $childArrays, $nonArrayItems);
      }
    }

    $attributes = self::getAllAttributes($type);
    foreach ($attributes as $attribute) {
      if ($attribute instanceof AttributeGroup) {
        $groupAttributes = $attribute->getAttributes();
        foreach ($groupAttributes as $groupAttr) {
          self::visitAttribute($tables, $groupAttr, $topParentName, $parentName, $childArrays, $nonArrayItems);
        }
      } else {
        self::visitAttribute($tables, $attribute, $topParentName, $parentName, $childArrays, $nonArrayItems);
      }
    }


    $typeName = self::getTypeName($type);
    foreach ($nonArrayItems as $childElem) {
      $elemName = $childElem->getName();
      $elemType = $childElem->getType();

      if ($elemType instanceof ComplexTypeSimpleContent) {
        $elemTypeName = self::getTypeName($elemType);
        $prefix = trim(str_replace($topParentName, '', $parentName), '/');
        if ($prefix) $prefix .= '/';

        $tables[$topParentName]['columns'][] = [
          'name' =>  $prefix . $elemName,
          'annotation' => $element->getDoc(),
          'schemaType' => $elemTypeName,
          'sourceNodeType' => 'tag'
        ];
      }

      if ($elemType instanceof ComplexType || $elemType instanceof ComplexTypeSimpleContent) {
        self::traverseType($tables, $elemType, $topParentName, $parentName . '/' . $elemName);
      }
    }
    foreach ($childArrays as $childArray) {
      $max = $childArray['max'];
      $element = $childArray['elem'];
      $max = $max == -1 ? 'unbounded' : $max;
      if ($element instanceof ElementRef) {
        $elementDef = $element->getReferencedElement();
        $elemName = $elementDef->getName();
        $elemType = $elementDef->getType();
      } else {
        $elemName = $element->getName();
        $elemType = $element->getType();
      }

      $elemTypeName = self::getTypeName($elemType);

      if ($parentName) {
        $printableParent = substr($parentName, strlen($topParentName) + 1);
      }
      $name = ($printableParent ? $printableParent . "/" : "") . $elemName;
      $tables[$topParentName]['relationships'][] = [
        'element' => $name,
        'type' => 'hasMany',
        'table' => $parentName . '/' . $elemName
      ];
      $tables[$parentName . '/' . $elemName] = [
        'name' => $parentName . '/' . $elemName,
        'columns' => [],
        'relationships' => [],
        'schemaType' => $elemTypeName
      ];

      if ($elemType instanceof ComplexTypeSimpleContent || $elemType instanceof SimpleType) {
        $tables[$parentName . '/' . $elemName]['columns'][] = [
          'name' => '#value',
          'annotation' => $element->getDoc(),
          'schemaType' => $elemTypeName,
          'sourceNodeType' => 'parent'
        ];
      }

      if ($elemType instanceof BaseComplexType) {
        self::traverseType($tables, $elemType, $parentName . '/' . $elemName, $parentName . '/' . $elemName);
      }
    }
  }
  private function visitElement(&$tables, $element, $topParentName, $parentName, &$childArrays, &$nonArrayItems) {
    if ($element instanceof ElementRef) {
      $elementDef = $element->getReferencedElement();
      $elemName = $elementDef->getName();
      $elemType = $elementDef->getType();
    } else {
      $elemName = $element->getName();
      $elemType = $element->getType();
    }

    $max = $element->getMax();
    $isArray = $max > 1 || $max == -1;
    if ($isArray) {
      $childArrays[] = [
        'max' => $max,
        'elem' => $element
      ];
    } else {
      $nonArrayItems[] = $element;
    }
    self::printField($tables, $element, $topParentName, $parentName, !$isArray);
  }
  private function printField(&$tables, $element, $topParentName = '', $parentName = '', $recursive = true) {
    $elemName = $element->getName();
    $elemType = $element->getType();
    $max = $element->getMax();
    $isArray = $max > 1 || $max == -1;
    $printableParent = '';
    if ($parentName) {
      $printableParent = substr($parentName, strlen($topParentName) + 1);
    }
    $name = ($printableParent ? $printableParent . "/" : "") . $elemName;
    $fullName = ($parentName ? $parentName . '/' : '') . $elemName;
    $arrayStr = '';
    if ($isArray) {
      $max = $max == -1 ? 'unbounded' : $max;
      $arrayStr = "(Array of $max)";
    }
    if (!$isArray && !($elemType instanceof BaseComplexType)) {
      $elemTypeName = self::getTypeName($elemType);
      $tables[$topParentName]['columns'][] = [
        'name' => $name,
        'annotation' => $element->getDoc(),
        'schemaType' => $elemTypeName,
        'sourceNodeType' => 'tag'
      ];
    }
  }
  private function visitAttribute(&$tables, $attribute, $topParentName, $parentName, &$childArrays, &$nonArrayItems) {
    if ($attribute instanceof AttributeRef) {
      $attributeDef = $attribute->getReferencedAttribute();
      $attrName = $attributeDef->getName();
      $attrType = $attributeDef->getType();
    } else {
      $attrName = $attribute->getName();
      $attrType = $attribute->getType();
    }

    $printableParent = '';
    if ($parentName) {
      $printableParent = substr($parentName, strlen($topParentName) + 1);
    }
    $name = ($printableParent ? $printableParent . "/" : "") . '@' . $attrName;

    $attrTypeName = self::getTypeName($attrType);
    $tables[$topParentName]['columns'][] = [
      'name' => $name,
      'annotation' => $attribute->getDoc(),
      'schemaType' => $attrTypeName,
      'sourceNodeType' => 'attribute'
    ];
  }

public function xmlToArray($xml, $path, $options = array()) {
    $defaults = array(
        'namespaceRecursive' => true, //set to true to get namespaces recursively, false if only namespaces in root
        'namespaceSeparator' => ':',//you may want this to be something other than a colon
        'removeNamespace' => false, //set to true if you want to remove the namespace from resulting keys
        'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
        'alwaysArray' => array(),   //array of xml tag names which should always become arrays
        'autoArray' => true,        //only create arrays for tags which appear more than once
        'textContent' => 'val',       //key used for the text content of elements
        'autoText' => false,         //skip textContent key if node has no attributes or child nodes
        'keySearch' => false,       //optional search and replace on tag and attribute names
        'keyReplace' => false       //replace values for above search values (as passed to str_replace())
    );



    $options = array_merge($defaults, $options);

    $namespaces = $xml->getDocNamespaces($options['namespaceRecursive']);

    $namespaces[''] = null; //add base (empty) namespace

    //get attributes from all namespaces
    $attributesArray = array();
    foreach ($namespaces as $prefix => $namespace) {
        if ($options['removeNamespace']) $prefix = "";
        foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
            //replace characters in attribute name
            if ($options['keySearch']) $attributeName =
                    str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
            $attributeKey = $options['attributePrefix']
                    . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                    . $attributeName;
            $attributesArray[$attributeKey] = (string)$attribute;
        }
    }

    //get child nodes from all namespaces
    $tagsArray = array();
    foreach ($namespaces as $prefix => $namespace) {
         if ($options['removeNamespace']) $prefix = "";
        foreach ($xml->children($namespace) as $currentChildName=>$childXml) {
            //recurse into child nodes

            //list($childTagName, $childProperties) = each($childArray);
            $childArray = self::xmlToArray($childXml, $path.".".$currentChildName, $options);
            list($childTagName, $childProperties) = each($childArray);

            //replace characters in tag name
            if ($options['keySearch']) $childTagName =
                    str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
            //add namespace prefix, if any
            if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;

            if (!isset($tagsArray[$childTagName])) {
                //only entry with this key
                //test if tags of this type should always be arrays, no matter the element count

                //echo $path.".".$currentChildName."\n";
                if (in_array($path.".".$currentChildName, $options['alwaysArray']) || !$options['autoArray']) {
                  $tagsArray[$childTagName] = array($childProperties);
                } else {
                  $tagsArray[$childTagName] = $childProperties;
                }

            } elseif (
                is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
                === range(0, count($tagsArray[$childTagName]) - 1)
            ) {
                //key already exists and is integer indexed array
                $tagsArray[$childTagName][] = $childProperties;
            } else {
                //key exists so convert to integer indexed array with previous value in position 0
                $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
            }
        }

    }

    //get text content of node
    $textContentArray = array();
    $plainText = trim((string)$xml);
    if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;

    //stick it all together
    $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
            ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;

    //return node as array
    return array(
        $xml->getName() => $propertiesArray
    );
}


}
