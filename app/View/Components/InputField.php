<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class InputField extends Component
{
    public $name;
    public $type;
    public $inline;
    public $row;
    public $placeholder;
    public $value;
    public $size;
    public $label;
    public $id;
    public $class;
    public $prepend;
    public $append;

    /**
     * Create a new component instance.
     *
     * @param string $name          Dot notation name, e.g. shipper.phone
     * @param string $type          Input type, e.g. text, email, checkbox, radio
     * @param string $placeholder   Placeholder text
     * @param mixed $value          Default value
     * @param string $size          Bootstrap size: sm, lg
     * @param string|null $label    Label text
     * @param string|null $id       HTML ID (defaults to slug of name)
     * @param string $class         Extra CSS classes
     * @param string|null $prepend  Input group prepend (text, HTML, or icon:class)
     * @param string|null $append   Input group append
     */
    public function __construct(
        string $name,
        string $type = 'text',
        bool $inline = false,
        bool $row = false,
        string $placeholder = '',
        $value = null,
        string $size = 'sm',
        ?string $label = null,
        ?string $id = null,
        string $class = '',
        ?string $prepend = null,
        ?string $append = null
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->inline = $inline;
        $this->row = $row;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->size = $size;
        $this->label = $label;
        $this->id = $id ?: Str::slug($name, '_');
        $this->class = $class;
        $this->prepend = $prepend;
        $this->append = $append;
    }

    /**
     * Convert dot notation into array syntax for HTML name attribute.
     *
     * Example: shipper.phone.code → shipper[phone][code]
     */
    public function arrayName(): string
    {
        $parts = explode('.', $this->name);
        $first = array_shift($parts);

        return $first . collect($parts)->map(fn($p) => "[$p]")->implode('');
    }

    /**
     * Determine if the input should be checked (for checkbox/radio).
     */
    public function isChecked(): bool
    {
        return (bool) old($this->name, $this->value);
    }

    public function render()
    {
        return view('components.input-field');
    }
}
