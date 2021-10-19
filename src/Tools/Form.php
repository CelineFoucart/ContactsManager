<?php

namespace App\Tools;

/**
 * Form
 * 
 * @param array  $errors
 * @param object $data
 */
class Form {
    
    /**
     * @var $data an object
     */
    private $data = null;

    private array $errors;

    private string $invalidInputClass = "is-invalid";

    private string $invalidFeedbackClass = "invalid-feedback";

    private string $inputClass = "form-control";

    private string $formGroupClass = "form-group";

    private array $toolbars = [];

    public function __construct(array $errors, $data = null, array $toolbars = [])
    {
        if ($data !== null) {
            $this->data = $data;
        }
        $this->errors = $errors;
        $this->toolbars = $toolbars;
    }

    protected function getErrors(string $key) 
    {
        $invalid = (!empty($this->errors[$key])) ? $this->invalidInputClass : "";
        
        if (!empty($this->errors[$key])) {
            if (is_array($this->errors[$key])) {
                $error = implode('<br/>', $this->errors[$key]);
            } else {
                $error = $this->errors[$key];
            }
        } else {
            $error = "";
        }

        return [$invalid, $error];
    }

    /**
     * Return the content of the value of an input
     *
     * @param  string $key
     * @return mixed
     */
    protected function getValue(string $key)
    {
        $value = "";
        if($this->data === null) {
            return "";
        } elseif (is_array($this->data)) {
            $value = $this->data[$key] ?? null;
        }
        $method = "get" . str_replace(" ", "", ucwords(str_replace("_", " ", $key)));
        if (method_exists($this->data, $method)) {
            $value = $this->data->$method();
        }
        
        if ($value instanceof \DateTimeInterface) {
            return $value->format("Y-m-d H:i:s");
        }
        return $value;
    }

    /**
     * returns an input
     *
     * @param  string $key     input name
     * @param  string $label   input label content
     * @param  array  $options options
     * @return string
     */
    public function input(string $key, string $label, array $options = []): string
    {
        [$invalid, $error] = $this->getErrors($key);
        $value = $this->getValue($key);
        $toolbar = isset($this->toolbars[$key]) ? $this->toolbars[$key] : "";

        $type = (isset($options['type'])) ? $options['type'] : "text";
        $invalidFeedbackClass = $this->invalidFeedbackClass;
        $classes = $this->inputClass . ' ' . $invalid;
        $formGroupClass = $this->formGroupClass;
        
        if (isset($options['autocomplete'])) {
            $autocomplete = 'autocomplete="' . $options['autocomplete'] . '"';
        } else {
            $autocomplete = "";
        }

        if (!empty($options['placeholder'])) {
            $placeholder = $options['placeholder'];
        } else {
            $placeholder = "";
        }

        if (!empty($options['id'])) {
            $id = $options['id'];
        } else {
            $id = $key;
        }

        $required = "required";
        if(isset($options['optional'])) {
            $required = "";
        }

        if(isset($options['label']) && $options['label'] === "none") {
            $labelTag = "";
        } else {
            $labelTag = "<label for=\"$key\">$label</label>";
        }

        if (!empty($options['value'])) {
            $value = htmlspecialchars($options['value']);
        }

        if($type === 'password') {
            $value = "";
        }

        return <<<HTML
        <div class="{$formGroupClass}">
            {$labelTag}
            {$toolbar}
            <input type="{$type}" name="{$key}" id="{$id}" class="{$classes}" value="{$value}" placeholder="{$placeholder}" {$required} {$autocomplete}>
            <div class="{$invalidFeedbackClass}">
                {$error}
            </div>
        </div>
HTML;
    }

    /**
     * Create an input type file
     * 
     * @param string $key
     * @param string $label
     * @param string $type
     * @return string
     */
    public function file(string $key, string $label, string $type = 'image'): string
    {
        [$invalid, $error] = $this->getErrors($key);
        $classes = $this->inputClass . ' ' . $invalid;        
        
        if($type === 'image') {
            $accept = 'image/png, image/jpeg, image/gif, image/jpg';
        } elseif($type === "audio") {
            $accept = 'audio/*';
        } elseif($type === 'video') {
            $accept = 'video/*';
        } else {
            $accept = '.doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        }

        $html = '<div class=" '. $this->formGroupClass .' ">';
        $html .= "<label for=\"$key\">$label</label>";
        $html .= '<input type="file" name="'.$key.'" id="'.$key.'" class="'. $classes. '" accept="'. $accept . '" required />';
        $html .= '<div class="'. $this->invalidFeedbackClass .'">'. $error. '</div></div>';

        return $html;
    }
    
    /**
     * create a textarea
     *
     * @param  mixed $key
     * @param  mixed $label
     * @return string
     */
    public function textarea(string $key, string $label, array $options = []): string
    {
        [$invalid, $error] = $this->getErrors($key);
        $value = $this->getValue($key);
        $invalidFeedbackClass = $this->invalidFeedbackClass;
        $classes = $this->inputClass . ' ' . $invalid;
        $formGroupClass = $this->formGroupClass;
        $placeholder = (!empty($options['placeholder'])) ? $options['placeholder'] : "";
        $toolbar = isset($this->toolbars[$key]) ? $this->toolbars[$key] : "";

        if (!empty($options['row'])) {
            $number = (int)$options['row'];
            $row = ($number < 1) ? 5 : $number;
        } else {
            $row = 5;
        }

        if (!empty($options['id'])) {
            $id = $options['id'];
        } else {
            $id = $key;
        }

        return <<<HTML
        <div class="{$formGroupClass}">
            <label for="{$key}">{$label}</label>
            {$toolbar}
            <textarea name="{$key}" id="{$id}" class="{$classes}" rows="{$row}" placeholder="{$placeholder}" required>{$value}</textarea>
            <div class="{$invalidFeedbackClass}">
                {$error}
            </div>
        </div>
HTML;
    }

    /**
     * @param string $key       field name
     * @param string $label     label content
     * @param array  $options   array of values [$id]['name'=> $name, 'selected'=> '']
     * @param string $required  
     * 
     * @return string
     */
    public function select(
        string $key, 
        string $label, 
        array $options, 
        bool $multiple = false, 
        string $required = "required"
    ): string
    {
        [$invalid, $error] = $this->getErrors($key);
        $invalidFeedbackClass = $this->invalidFeedbackClass;
        $optionsHTML = [];    
        foreach ($options as $id => $option) {         
            if (isset($option['selected']) && $option['selected'] === "selected") {
                $selected = $option['selected'];
            } else {
                $selected = '';  
            }
            $optionsHTML[] = "<option value=\"$id\" $selected>". $option['name'] ."</option>";
        }
        if ($required !== "required") {
            $required = "";
        }
        $optionsHTML = implode('', $optionsHTML);
        $classes = $this->inputClass . ' ' . $invalid;
        $formGroupClass = $this->formGroupClass;
        if($multiple) {
            $multipleHTML = 'multiple';
            $keyname = $key . '[]';
        } else {
            $multipleHTML = '';
            $keyname = $key;
        }

        $multipleHTML = $multiple ? 'multiple' : '';  
        
        return <<<HTML
        <div class="{$formGroupClass}">
            <label for="{$key}">{$label}</label>
            <select name="{$keyname}" id="{$key}" class="{$classes}" {$multipleHTML} {$required}>{$optionsHTML}</select>
            <div class="{$invalidFeedbackClass}">
                {$error}
            </div>
        </div>
HTML;
    }
}