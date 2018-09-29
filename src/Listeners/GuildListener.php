<?php

namespace SwgohHelp;

use JsonStreamingParser\Listener;

class GuildListener implements Listener {

    protected $json;

    protected $stack;
    protected $keys;

    protected $level;

    /**
     * @var Callable
     */
    protected $callback;

    /**
     *
     * @param Callable $callback
     */
    public function __construct(Callable $callback = null) {
        $this->callback = $callback;
    }

    public function getJson() { return $this->json; }

    public function startDocument() {
        $this->stack = [];
        $this->level = 0;
        $this->keys = [];
    }
    public function endDocument() { }

    public function startObject() {
        $this->level += 1;
        $this->startComplexValue('object');
    }
    public function endObject() {
        $this->endComplexValue();
        $this->level -= 1;
    }

    public function startArray() {
        $this->startComplexValue('array');
    }
    public function endArray() {
        $this->endComplexValue();
    }

    public function key($key) {
        $this->keys[] = $key;
    }
    public function value($value) {
        $this->insertValue($value);
    }
    public function whitespace($whitespace) { }

    protected function startComplexValue($type) {
        // We keep a stack of complex values (i.e. arrays and objects) as we build them,
        // tagged with the type that they are so we know how to add new values.
        $current_item = ['type' => $type, 'value' => []];
        $this->stack[] = $current_item;
    }
    protected function endComplexValue() {
        $obj = array_pop($this->stack);
        // If the value stack is now empty, we're done parsing the document, so we can
        // move the result into place so that getJson() can return it. Otherwise, we
        // associate the value
        if (empty($this->stack)) {
            $this->json = $obj['value'];
        } else {
            $this->insertValue($obj['value']);
        }
    }
    // Inserts the given value into the top value on the stack in the appropriate way,
    // based on whether that value is an array or an object.
    protected function insertValue($value) {
        // Grab the top item from the stack that we're currently parsing.
        $current_item = array_pop($this->stack);
        // Examine the current item, and then:
        //   - if it's an object, associate the newly-parsed value with the most recent key
        //   - if it's an array, push the newly-parsed value to the array
        if ($current_item['type'] === 'object') {
            $current_item['value'][array_pop($this->keys)] = $value;
        } else {
            if (head($this->keys) == 'roster' && $this->level == 2) {
                if (is_callable($this->callback)) {
                    call_user_func($this->callback, $value);
                }
            } else {
                $current_item['value'][] = $value;
            }
        }
        // Replace the current item on the stack.
        $this->stack[] = $current_item;
    }
}