<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Collection
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Implements an array of objects.
 *
 * Similar to an array, it contains components that can be accessed
 * using an integer index. The size of a Blueberry_Collection
 * can grow or shrink as needed to accommodate adding and removing
 * items after the Blueberry_Collection has been created.
 *
 * A Blueberry_Collection can be used in a foreach loop, like:
 * <code>
 * $collection = new Blueberry_Collection('one', 'two', 'three')
 * foreach($collection as $item){
 *
 *		echo $item.' ';
 *
 * }
 * </code>
 *
 * will produce
 *
 * <code>
 * one two three
 * </code>
 *
 */
class Blueberry_Collection implements Iterator {

	/**
	 * The inner array.
	 *
	 * This is where all the elements are stored.
	 */
	protected $array = array();

	/**
	 * Necessary for foreach() looping.
	 *
	 * Used by PHP's native Iterator interface to allow
	 * foreach loops on this object.
	 */
	protected $valid = false;

	/**
	 * @param mixed $elements,...
	 */
	public function __construct()
	{
		foreach(func_get_args() as $a){
			$this->add($a);
		}
	}

	/**
	 * Adds an element to the end of the stack
	 *
	 * Example:
	 * <code>
	 * $collection = new Blueberry_Collection('one', 'two', 'three')
	 * $collection->add('four');
	 *
	 * // will produce "one, two, three, four"
	 * echo $collection;
	 * </code>
	 *
	 * @param mixed $element the element to add
	 */
	public function add($element)
	{
		foreach(func_get_args() as $e){
			$this->array[] = $e;
		}
	}

	/**
	 * Alias of {@link add()}
	 *
	 * @param mixed $element the element to add
	 * @see add()
	 */
	public function append($element)
	{
		$this->add($element);
	}

	/**
	 * Adds an element to the beginning of the stack
	 *
	 * The indices of all original elements are incremented by
	 * one, similar to PHP's {@link PHP_MANUAL#array_unshift array_unshift}
	 * function.
	 *
	 * Example:
	 * <code>
	 * $collection = new Blueberry_Collection('apple', 'orange', 'pear')
	 * $collection->prepend('banana');
	 *
	 * // will produce "banana, apple, orange, pear"
	 * echo $collection;
	 * </code>
	 *
	 * @param mixed $element the element to prepend
	 */
	public function prepend($element){

		array_unshift($this->array, $element);

	}

	/**
	 * Counts the elements in the stack
	 *
	 * Example:
	 * <code>
	 * $collection = new Blueberry_Collection('apple', 'orange', 'pear')
	 *
	 * // will produce "3"
	 * echo $collection->amount();
	 * </code>
	 *
	 * @return int the amount of elements in the stack
	 */
	public function amount(){

		return count($this->array);

	}

	/**
	 * Determines whether the stack is empty
	 *
	 * @return bool true if empty, false otherwise
	 */
	public function isEmpty(){

		return count($this->array) === 0;

	}

	/**
	 * Fetches the last element on the stack
	 *
	 * The last element (or its reference) is returned whilst keeping
	 * the entire stack (including the element that is fetched) entirely
	 * intact.
	 *
	 * Example:
	 * <code>
	 * $collection = new Blueberry_Collection('apple', 'orange', 'pear')
	 *
	 * // will produce "pear"
	 * echo $collection->getLastElement();
	 * </code>
	 *
	 * @return object|mixed the element
	 */
	public function getLastElement(){

		return $this->get(count($this->array)-1);

	}

	/**
	 * Fetches the first element on the stack
	 *
	 * The first element (or its reference) is returned whilst keeping
	 * the entire stack (including the element that is fetched) entirely
	 * intact.
	 *
	 * Example:
	 * <code>
	 * $collection = new Blueberry_Collection('apple', 'orange', 'pear')
	 *
	 * // will produce "apple"
	 * echo $collection->getFirstElement();
	 * </code>
	 *
	 * @return object|mixed the element
	 */
	public function getFirstElement(){

		return $this->get(0);

	}

	/**
	 * Fetches an element on the stack
	 *
	 * The requested element (or its reference) is returned whilst keeping
	 * the entire stack (including the element that is fetched) entirely
	 * intact.
	 *
	 * Example:
	 * <code>
	 * $collection = new Blueberry_Collection('apple', 'orange', 'pear')
	 *
	 * // will produce "orange"
	 * echo $collection->get(1);
	 * </code>
	 *
	 * @param int $index the index of the requested element
	 * @return object|mixed the element
	 */
	public function get($index){

		return $this->array[$index];

	}

	public function rewind(){$this->valid = (false !== reset($this->array));}

	public function current(){return current($this->array);}

	public function key(){return key($this->array);}

	public function next(){$this->valid = (false !== next($this->array));}

	/**
	 * Removes all elements from the stack.
	 *
	 * Effectively clears the stack, so it's like you're
	 * starting out with a new collection.
	 *
	 */
	public function clear(){

		$this->array = array();

	}

	public function valid(){return $this->valid;}

	/**
	 * Returns an array containing all of the elements in the stack in the correct order.
	 *
	 * @return array the array containing all of the elements in the stack in the correct order.
	 */
	public function toArray(){

		return $this->array;

	}

	/**
	 * Replaces an element in the stack.
	 *
	 * @param int $index the index of the item to replace
	 * @param mixed $element the new element
	 */
    public function replace($index, $element){

        $this->array[$index] = $element;

    }

	/**
	 * Returns a string representation of the stack.
	 *
	 * Example:
	 * <code>
	 * $collection = new Blueberry_Collection('one', 'two', 'three')
	 *
	 * // will produce "one, two, three"
	 * echo $collection;
	 * </code>
	 *
	 * PHP will {@link PHP_MANUAL#oop5.magic automagically} call the
	 * __toString() method on any objects in the stack.
	 *
	 * @return string a string representation of the stack, separated by commas.
	 */
	public function __toString(){

		return implode(', ', $this->array);

	}
}


