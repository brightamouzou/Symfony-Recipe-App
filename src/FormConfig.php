<?php

    namespace App;

    class FormConfig{
        
        public $minLength; 
        public $maxLength;
        public $class; 
        public $min; 
        public $max;
        public $label;
        public $label_attr=['class'=>"mb-1"];
        public $required=true;
        public $type='text';
        public $placeholder='';
        
        
        public function textConfig($class,$label='',$labelAttr= ['class' => "mb-2"],$placeholder= '', $minLength='0',$maxLength=''){
            $this->class=$class;
            $this->minLength=$minLength;
            $this->maxLength=$maxLength;
            $this->label = $label;
            $this->label_attr = $labelAttr;
            $this->placeholder = $placeholder;

            return [
                'attr'=>[
                    'class' => $this->class. " mb-3",
                    'minLength' => $this->minLength,
                    'maxLength' => $this->maxLength
                ],
                'label'=>$this->label,
                'label_attr'=>$this->label_attr,
                // 'placeholder' => $this->placeholder,
                
            ];
        }

        public function numberConfig($class,$label='', $labelAttr = array(['class' => ""]), $placeholder='',$min= '0', $max = null, $required='required'){
            $this->class = $class . " mb-3";
            $this->min = $min;
            $this->max = $max;
            $this->label = $label;

            // $this->label_attr = $labelAttr;
            // $this->placeholder = $placeholder;
            return [
                'attr' => [
                    'required'=> $required,
                    'class'=>$this->class,
                    'min'=>$this->min,
                    'max'=>$this->max
                ],
                'label' => $this->label,
                'label_attr' => $this->label_attr,
                // 'placeholder' => $this->placeholder,
        ];

        }

     


    }

?>