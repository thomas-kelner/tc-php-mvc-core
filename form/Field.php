<?php

namespace app\core\form;

use app\models\Model;

class Field 
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';

    public string $type;
    public Model $model;
    public string $attribute;

   public function __construct(Model $model, string $attribute)
   {
        $this->type = self::TYPE_TEXT;
        $this->model = $model;
        $this->attribute = $attribute;
   }

   public function __toString(): string
   {
        return sprintf('
            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="%s" style="display: block; margin-bottom: 0.5rem;">%s</label>
                <input type="%s" id="%s" name="%s" value="%s" style="width: 100%%; padding: 0.5rem; font-size: 1rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" class="%s">
                <div class="invalid-feedback" style="color: red; font-size: 0.875rem; margin-top: 0.25rem;">
                    %s
                </div>
            </div>
        ', 
            $this->attribute, 
            ucfirst($this->model->getLabel($this->attribute)),
            $this->type, 
            $this->attribute, 
            $this->attribute, 
            $this->model->{$this->attribute}, 
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
            $this->model->getFirstError($this->attribute)
        );
   }

   public function passwordField()
   {
        $this->type = self::TYPE_PASSWORD;
        return $this;
   }
          
}