<?php

namespace GridBundle\Components\Grid\Filter;

use GridBundle\Components\Grid\Exceptions\GridException;
use GridBundle\Components\Grid\Filter\Fields\Date;
use GridBundle\Components\Grid\Filter\Fields\Field;
use GridBundle\Components\Grid\Filter\Fields\Text;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class Filter
{
    /** @var Field[] */
    private $fields = [];
    /**
     * @var Container
     */
    private $container;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name, Container $container, Request $request)
    {
        $this->container = $container;
        $this->request   = $request;
        $this->name      = $name;
    }

    public function addField(Field $field)
    {
        $this->fields[$field->getName()] = $field;

        return $this;
    }

    /**
     * @return Field[]
     */
    public function getFields() : array
    {
        return $this->fields;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm()
    {
        $formBuilder = $this->container->get('form.factory')->createBuilder(FormType::class);

        foreach ($this->fields as $field) {
            $formBuilder->add($field->getName(), $field->getFormFieldType(), $field->getFormFieldOptions());
        }

        return $formBuilder->getForm();
    }

    /**
     * Zpracuje se request a nastaví se hodnoty fieldů pro použití
     */
    public function handleRequest()
    {
        $allData = $this->request->request->all();
        $session = $this->request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $sessionValues = $session->get($this->getFilterSessionName());
        if (!is_array($sessionValues)) {
            $sessionValues = [];
        }

        // data odeslány z filtru
        if (isset($allData['form']) && count($allData['form'])) {
            foreach ($allData['form'] as $fieldName => $value) {
                if (isset($this->fields[$fieldName])) {
                    $field = $this->fields[$fieldName];
                } else {
                    continue;
                }

                if ($field instanceof Field) {
                    $field->setValue($value);
                    $sessionValues[$field->getName()] = $value;
                }

            }
            $session->set($this->getFilterSessionName(), $sessionValues);
        } else { // data nejsou odeslána z filtru, použijí se ze session nebo defaultní (defaultní je už ve fieldu)
            foreach ($this->fields as $field) {
                if (isset($sessionValues[$field->getName()])) {
                    $field->setValue($sessionValues[$field->getName()]);
                }
            }
        }
    }

    private function getFilterSessionName()
    {
        return $this->name . '-filer';
    }
}
