<?php

/**
 * Class ElementMvc_Stdlib_ArrayList
 */
class ArrayList implements
	ArrayAccess,	/** Interface to provide accessing objects as arrays...but here - strictly an integer indexed array... */
	Countable,
	Iterator,
	Serializable,
	SeekableIterator
{
	/**
	 * Array, representing the arrayList
	 **/
	private $arrayList;

	/**
	 * @var null|self
	 */
	protected static $instance = null;

	/**
	 * @param string $arr
	 */
	public function __construct($arr = null)
	{
		$this->arrayList = (is_array($arr) == true) ? $arr : array();
	}

	/**
	 * @param null $arr
	 * @return ElementMvc_Stdlib_ArrayList|null
	 */
	public static function getInstance($arr = null)
	{
		if (self::$instance==null){
			self::$instance = new self($arr);
		}
		return self::$instance;
	}

	/**
	 * @param mixed $index
	 * @return bool
	 */
	public function offsetExists($index)
	{
		return isset($this->arrayList[$index]);
	}

	/**
	 * @param mixed $index
	 * @return mixed
	 * @throws Exception
	 */
	public function offsetGet($index)
	{
		if ($this->isInteger($index)) {
			return $this->arrayList[$index];
		}
		else {
			$functionName = __CLASS__."::".__METHOD__;
			throw new Exception("ERROR in {$functionName} <br> Index MUST be an Integer value");
		}
	}

	/**
	 * @param mixed $index
	 * @param mixed $value
	 * @throws Exception
	 */
	public function offsetSet($index, $value)
	{
		if ($this->isInteger($index)){
			$this->arrayList[$index] = $value;
		}
		else {
			$functionName = __CLASS__."::".__METHOD__;
			throw new Exception("ERROR in {$functionName} <br> Index MUST be an Integer value");
		}
	}

	/**
	 * @param mixed $index
	 * @throws Exception
	 */
	public function offsetUnset($index)
	{
		if ($this->isInteger($index)) {
			$newArrayList = array();
			$size = $this->size();
			for ($i=0; $i < $size; $i++) {
				if ($index != $i) {
					$newArrayList[] = $this->offsetGet($i);
				}
			}

			$this->arrayList = $newArrayList;
		}
		else {
			$functionName = __CLASS__."::".__METHOD__;
			throw new Exception("ERROR in {$functionName} <br> Index MUST be an Integer value");
		}
	}

	/**
	 * Returns the number of elements in this list.
	 * return integer
	 **/
	public function count()
	{
		return count($this->arrayList);
	}

	/**
	 * Returns the element at the specified position in this list.
	 * @param $index
	 * @return mixed
	 * @throws Exception
	 */
	public function get($index)
	{
		return $this->offsetGet($index);
	}

	/**
	 * @return mixed
	 */
	public function getFirst()
	{
		return reset($this->arrayList);
	}

	/**
	 * @return mixed
	 */
	public function getLast()
	{
		return end($this->arrayList);
	}

	/**
	 * Tests if this list has no elements.
	 * @return boolean
	 **/
	public function isEmpty()
	{
		return ($this->count() == 0);
	}

	/**
	 * Removes all of the elements from this list.
	 **/
	public function clear()
	{
		$this->arrayList = array();
	}

	/**
	 * Appends the specified element to the end of this list.
	 * @param $obj
	 * @return int the new number of elements in the array.
	 */
	public function add($obj)
	{
		return array_push($this->arrayList, $obj);
	}

	/**
	 * Appends all of the elements in the specified Array to the end of this list
	 * @param arr - one dimensional array
	 **/
	public function addAll($arr)
	{
		$this->arrayList = array_merge($this->arrayList, $arr);
	}

	/**
	 * Inserts the specified element at the specified position in this list.
	 * @param $key
	 * @param $obj
	 * @throws Exception
	 */
	public function addOrReplaceAt($key, $obj)
	{
		$this->offsetSet($key, $obj);
	}

	/**
	 * Returns true if this list contains the specified element.
	 * @param obj
	 * @return boolean
	 **/
	public function contains($obj)
	{
		return in_array($obj, $this->arrayList);
	}

	/**
	 * @param $index
	 * @return bool
	 */
	public function containKey($index)
	{
		return $this->offsetExists($index);
	}

	/**
	 * Searches for the first occurrence of the given argument. If the element isn´t found, -1 is returned
	 * @param obj
	 * @return integer
	 **/
	public function indexOf($obj)
	{
		foreach($this->arrayList as $key => $val)
		{
			if ($obj == $val) return $key;
		}
		return -1;
	}

	/**
	 * Searches the array for a given value and returns the first corresponding key if successful.
	 * Same as @indexOf
	 * @param $obj
	 * @return mixed
	 */
	public function lastIndexOf($obj)
	{
		return array_search($obj, $this->arrayList);
	}

	/**
	 * Returns the first key in an arrayList.
	 *
	 * @param  array $array
	 * @return int|string
	 */
	public function firstKey(array $array)
	{
		reset($array);
		return key($array);
	}

	/**
	 * Returns the last key in an arrayList.
	 *
	 * @param  array $array
	 * @return int|string
	 */
	public function lastKey(array $array)
	{
		end($array);
		return key($array);
	}

	/**
	 * removes the element at the specified position in this list.
	 * @param $index
	 * @throws Exception
	 */
	public function remove($index)
	{
		$this->offsetUnset($index);
	}

	/**
	 * Removes from this List all of the elements whose index is between fromIndex, inclusive and toIndex, exclusive.
	 * @param $fromIndex
	 * @param $toIndex
	 * @throws Exception
	 */
	public function removeRange($fromIndex, $toIndex)
	{
		if ($this->isInteger($fromIndex) && $this->isInteger($toIndex)) {
			$newArrayList = array();
			$size = $this->size();
			for ($i=0; $i < $size; $i++){
				if ($i < $fromIndex || $i > $toIndex ) {
					$newArrayList[] = $this->offsetGet($i);
				}
			}

			$this->arrayList = $newArrayList;
		}
		else{
			$functionName = __CLASS__."::".__METHOD__;
			throw new Exception("ERROR in $functionName <br> Index MUST be an Integer value");
		}
	}

	/**
	 * @return mixed
	 */
	public function removeFirst()
	{
		return array_shift($this->arrayList);
	}

	/**
	 * @return int
	 */
	public function removeLast()
	{
		return array_pop($this->arrayList);
	}

	/**
	 * Returns the number of elements in this list.
	 * return integer
	 **/
	public function size()
	{
		return $this->count();
	}

	/* Sort Methods */

	/**
	 * Sorts the list in alphabetical order. Keys are not kept in position.
	 * @return bool
	 */
	public function sort()
	{
		return sort($this->arrayList);
	}

	/**
	 * Sort an array by key
	 * @return bool
	 */
	public function ksort()
	{
		return ksort($this->arrayList);
	}

	/**
	 * Sort an array by key in reverse order
	 * @return bool
	 */
	public function krsort()
	{
		return krsort($this->arrayList);
	}

	/**
	 * Sort array by values
	 * @return bool
	 */
	public function asort()
	{
		return asort($this->arrayList);
	}

	/**
	 * @param $cmp_function
	 * @return bool
	 * User defined sort
	 * @param string $cmp_function : The compare function used for the sort.
	 */
	public function uasort($cmp_function)
	{
		return uasort($this->arrayList, $cmp_function);
	}

	/**
	 * User defined sort
	 * @param string $cmp_function : The compare function used for the sort.
	 * @param $cmp_function
	 * @return bool
	 */
	public function uksort($cmp_function)
	{
		return uksort($this->arrayList, $cmp_function);
	}

	/**
	 * Sort an array naturally
	 * @return bool
	 */
	public function natsort()
	{
		return natsort($this->arrayList);
	}

	/**
	 * Sort an array naturally, case insensitive
	 * @return bool
	 */
	public function natcasesort()
	{
		return natcasesort($this->arrayList);
	}

	/* convert Array List to Array */

	/**
	 * Returns an array containing all of the elements in this list in the correct order.
	 * @return array
	 **/
	public function toArray()
	{
		return $this->arrayList;
	}

	/* Iterator Methods */

	/**
	 * Returns true if the list has more elements.
	 * @return boolean
	 **/
	public function hasNext()
	{
		$next = $this->next();
		$this->prev();
		return ($next!=false);
	}

	/**
	 * @param int $position
	 */
	public function seek($position)
	{
		$this->reset();
		$i=0;
		while($i < $position){
			$this->next();
			$i++;
		}
	}

	/**
	 * Set the pointer of the list to the first element
	 **/
	public function reset()
	{
		return reset($this->arrayList);
	}

	/**
	 * @return mixed
	 */
	public function prev()
	{
		return prev($this->arrayList);
	}

	/**
	 * Return the current element
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 * @since 5.0.0
	 */
	public function current()
	{
		return current($this->arrayList);
	}

	/**
	 * Advance the internal array pointer of an array
	 * @return mixed the array value in the next place that's pointed to by the
	 * internal array pointer, or false if there are no more elements.
	 */
	public function next()
	{
		return next($this->arrayList);
	}

	/**
	 * Return the key of the current element
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 * @since 5.0.0
	 */
	public function key()
	{
		return key($this->arrayList);
	}

	/**
	 * Checks if current position is valid
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 * @since 5.0.0
	 */
	public function valid()
	{
		$current = $this->current();
		return (!empty($current));
	}

	/**
	 * Rewind the Iterator to the first element
	 * @link http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	public function rewind()
	{
		$this->reset();
	}

	/**
	 * String representation of object
	 * @link http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 * @since 5.1.0
	 */
	public function serialize()
	{
		return serialize($this->arrayList);
	}

	/**
	 * Constructs the object
	 * @link http://php.net/manual/en/serializable.unserialize.php
	 * @param string $serialized <p>
	 * The string representation of the object.
	 * </p>
	 * @return void
	 * @since 5.1.0
	 *
	 * Not 100% confidence about the implementation of this function.
	 */
	public function unserialize($serialized)
	{
		unserialize($serialized);
	}

	/**
	 * @param $value
	 * @return bool
	 * Returns true if the parameter holds an integer value
	 */
	public function isInteger($value)
	{
		return is_int($value);
	}

}
?>